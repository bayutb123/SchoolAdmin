<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryRequest;
use App\Http\Requests\AddInitialInventoryRequest;
use Carbon\Carbon;

use App\Http\Requests\InventoryRequestRequest;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $inventory = \App\Models\Inventory::all()->reverse();
        $status = \App\Models\Status::all();

        $statusApproved = $status->where('name', 'Disetujui')->first()->id;
        $statusInProcess = $status->where('name', 'Dalam Proses')->first()->id;
        $statusInExpedition = $status->where('name', 'Dalam Pengiriman')->first()->id;
        $statusInRepair = $status->where('name', 'Dalam Perbaikan')->first()->id;
        
        $requestStatus = collect([$statusApproved, $statusInProcess, $statusInExpedition]);

        $statusPlanned = $status->where('name', 'Rencana Pembelian')->first()->id;

        foreach ($inventory as $item) {
            $item->statusName = $status->where('id', $item->status)->first()->name;
            $item->statusColor = $status->where('id', $item->status)->first()->color;

            if ($item->created_at->isToday()) {
                $item->isNew = true;
            }

            if ($item->issue_status) {
                if ($item->issue_id) {
                    $item->isIssued = true;
                }
                if ($item->issue_status == $statusApproved) {
                    $item->isApproved = true;
                } else if ($item->issue_status == $statusInRepair) {
                    $item->isApproved = true;
                }
                $item->issueStatusName = $status->where('id', $item->issue_status)->first()->name;
                $item->issueStatusColor = $status->where('id', $item->issue_status)->first()->color;
            }

            if ($item->request_status) {
                if ($item->request_id) {
                    $item->isRequested = true;
                }
                if ($requestStatus->contains($item->request_status)) {
                    $item->isApproved = true;
                } else if ($item->request_status == $statusPlanned) {
                    $item->isPlanned = true;
                } 
                $item->requestStatusName = $status->where('id', $item->request_status)->first()->name;
                $item->requestStatusColor = $status->where('id', $item->request_status)->first()->color;
            }

            // jika quantity berisi .00, maka tidak perlu menampilkan koma
            if (strpos($item->quantity, '.00') !== false) {
                $item->quantity = number_format($item->quantity, 0, ',', '.');
            } else {
                $item->quantity = number_format($item->quantity, 2, ',', '.');
            }

            if ($item->room_id) {
                $item->roomName = \App\Models\Room::where('id', $item->room_id)->first()->name;
            }
        }

        $widget = [
            'inventory' => $inventory,
        ];
        
        return view('inventory', compact('widget'));
    }

    public function create()
    {
        $rooms = \App\Models\Room::all();
        $category = Inventory::select('category')->distinct()->get();
        $status = \App\Models\Status ::where('type', 'inventory')
            ->where('id', '<', 5) // filter status where id < 5
            ->get();
        $widget = [
            'rooms' => $rooms,
            'status' => $status,
            'category' => $category,
        ];
        return view('inven.add' , compact('widget'));
    }

    public function store(AddInitialInventoryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $inventory = Inventory::create(
                [
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'name' => $request->name,
                    'description' => $request->description,
                    'quantity' => $request->quantity,
                    'quantity_unit' => $request->quantity_unit,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        } else {
            return redirect()->route('inventory.create')->withError('Error. Data tidak valid');
        }
        return redirect()->route('inventory.create')->withSuccess('Inventory added successfully.');
    }

    public function edit($id)
    {
        $inventory = Inventory::where('id', $id)->first();
        // get all category from all inventory
        $category = Inventory::select('category')->distinct()->get();
        $status = \App\Models\Status::where('type', 'inventory')
            ->where('id', '<', 5) // filter status where id < 5
            ->get();
        
        $rooms = \App\Models\Room::all();
        $widget = [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'category' => $category,
            'status' => $status,
        ];
        return view('inven.edit', compact('widget'));
    }

    // destroyItem
    public function destroyItem(Request $request) {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        if ($validated) {
            // check if inventory is request or issue
            $inventory = Inventory::where('id', $validated['id'])->first();
            if ($inventory->request_id) {
                return redirect()->route('inventory')->withError('Error. Inventory is requested.');
            } else if ($inventory->issue_id) {
                return redirect()->route('inventory')->withError('Error. Inventory is issued.');
            } else {
                $inventory = Inventory::where('id', $validated['id'])->delete();
            }
        }
        return redirect()->route('inventory')->withSuccess('Inventory deleted successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required',
            'name' => 'required',
            'room_id' => 'required',
            'category' => 'required',
            'description' => 'nullable',
            'quantity' => 'required',
            'status' => 'required',
            'last_author_id' => 'required',
        ]);

        if ($validated) {
            $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                [
                    'name' => $request->name,
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'description' => $request->description,
                    'quantity' => $request->quantity,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory')->withSuccess('Inventory updated successfully.');
    }

    public function request() {
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::where('name', 'Pengadaan')->get();

        $widget = [
            'rooms' => $rooms,
            'status' => $status,
        ];

        return view('inven.request' , compact('widget'));
    }

    public function requestStore(InventoryRequestRequest $request) {
        $validated = $request->validated();

        // get status where it name == 'Rencana Pembelian'
        $status = \App\Models\Status::where('name', 'Rencana Pembelian')->first();

        if ($validated) {
            $inventory = Inventory::create(
                [
                    'request_status' => $status->id, // status 'Rencana Pembelian
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'quantity_unit' => $request->quantity_unit,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory.request')->withSuccess('Request added successfully.');
    }

    public function requestEdit($id) {
        $inventory = Inventory::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $inventory->statusName = $status->where('id', $inventory->status)->first()->name;
        $widget = [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'status' => $status,
        ];
        return view('inven.editrequest', compact('widget'));
    }

    public function requestUpdate(Request $request) {
        $validated = $request->validate([
            'inventory_id' => 'required',
            'name' => 'required',
            'room_id' => 'required',
            'category' => 'required',
            'description' => 'nullable',
            'price' => 'required',
            'quantity' => 'required',
            'status' => 'required',
            'last_author_id' => 'required',
        ]);

        if ($validated) {
            $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                [
                    'name' => $request->name,
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory')->withSuccess('Inventory updated successfully.');
    }

    public function updateRequestStatus($id) {
        // check if inventory is request or issue
        $inventory = Inventory::where('id', $id)->first();
        $whiteList = [];
        if ($inventory->request_status) {
            $whiteList = ['Dalam Proses', 'Dalam Pengiriman', 'Diterima'];
        } else if ($inventory->issue_status) {
            $whiteList = ['Dalam Perbaikan', 'Selesai'];
        }

        $inventory = Inventory::where('id', $id)->first();
        $applicableStatus = \App\Models\Status::
            whereIn('name', $whiteList)
            ->get();
        $approvedStatus = \App\Models\Status::where('name', 'Disetujui')->first();

        // add approved status to applicable status but approvedstatus first
        $applicableStatus->prepend($approvedStatus);

        $widget = [
            'inventory' => $inventory,
            'applicableStatus' => $applicableStatus,
        ];
        return view('inven.updaterequest', compact('widget'));
    }

    public function checkIsFinished($id) {
        $inventory = Inventory::where('request_id', $id)->orWhere('issue_id', $id)->get();
        $isFinished = true;
        foreach ($inventory as $item) {
            if ($item->request_id) {
                if (!$item->request_status) {
                    $isFinished = false;
                }
            } else if ($item->issue_id) {
                if (!$item->issue_status) {
                    $isFinished = false;
                }
            }
        }
        return $isFinished;
    }

    public function updateRequestStatusStore(Request $request) {
        

        $validated = $request->validate([
            'inventory_id' => 'required',
            'status' => 'required',
        ]);

        $inventory = Inventory::where('id', $validated['inventory_id'])->first();
        if ($inventory->request_id) {
            $finalStatus = \App\Models\Status::where('name', 'Diterima')->first();
        } else if ($inventory->issue_id) {
            $finalStatus = \App\Models\Status::where('name', 'Selesai')->first();
        }

        // get status id where name is Baik
        $goodStatus = \App\Models\Status::where('name', 'Baik')->first();

        if ($validated) {
            if ($inventory->request_id) {
                if ($validated['status'] == $finalStatus->id) {
                    $request_id = \App\Models\InventoryRequest::where('id', $inventory->request_id)->first()->id;
                    
                    $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                        [
                            'request_id' => null, // remove request_id
                            'request_status' => null,
                            'status' => $goodStatus->id,
                        ]
                    );

                    if ($this->checkIsFinished($request_id)) {
                        $request = \App\Models\InventoryRequest::where('id', $request_id)->update(
                            [
                                'status' => $validated['status'],
                            ]
                        );
                    }
                    
                } else {
                    $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                        [
                            'request_status' => $validated['status'],
                        ]
                    );
                }

            } else if ($inventory->issue_id) {
                if ($validated['status'] == $finalStatus->id) {
                    $issue_id = \App\Models\InventoryIssue::where('id', $inventory->issue_id)->first()->id;
                    
                    $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                        [
                            'issue_id' => null, // remove issue_id
                            'issue_status' => null,
                            'status' => $goodStatus->id,
                        ]
                    );
                    if ($this->checkIsFinished($issue_id)) {
                        $issue = \App\Models\InventoryIssue::where('id', $issue_id)->update(
                            [
                                'status' => $validated['status'],
                            ]
                        );
                    }
                    
                } else {
                    $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                        [
                            'issue_status' => $validated['status'],
                        ]
                    );
                }
            }
            
        }
        return redirect()->route('inventory')->withSuccess('Inventory updated successfully.');
    }

    // export to pdf
    public function print() {

        $inventories = Inventory::all();
        $status = \App\Models\Status::all();
        $room = \App\Models\Room::all();    

        $statusApproved = $status->where('name', 'Disetujui')->first()->id;
        $statusInProcess = $status->where('name', 'Dalam Proses')->first()->id;
        $statusInExpedition = $status->where('name', 'Dalam Pengiriman')->first()->id;
        $statusInRepair = $status->where('name', 'Dalam Perbaikan')->first()->id;
        
        $requestStatus = collect([$statusApproved, $statusInProcess, $statusInExpedition]);

        $statusPlanned = $status->where('name', 'Rencana Pembelian')->first()->id;

        foreach ($inventories as $item) {
            $item->statusName = $status->where('id', $item->status)->first()->name;
            $item->statusColor = $status->where('id', $item->status)->first()->color;
            $item->roomName = $room->where('id', $item->room_id)->first()->name;

            if ($item->issue_status) {
                if ($item->issue_id) {
                    $item->isIssued = true;
                }
                if ($item->issue_status == $statusApproved) {
                    $item->isApproved = true;
                } else if ($item->issue_status == $statusInRepair) {
                    $item->isApproved = true;
                }
                $item->issueStatusName = $status->where('id', $item->issue_status)->first()->name;
                $item->issueStatusColor = $status->where('id', $item->issue_status)->first()->color;
            }

            if ($item->request_status) {
                if ($item->request_id) {
                    $item->isRequested = true;
                }
                if ($requestStatus->contains($item->request_status)) {
                    $item->isApproved = true;
                } else if ($item->request_status == $statusPlanned) {
                    $item->isPlanned = true;
                } 
                $item->requestStatusName = $status->where('id', $item->request_status)->first()->name;
                $item->requestStatusColor = $status->where('id', $item->request_status)->first()->color;
            }

            // jika quantity berisi .00, maka tidak perlu menampilkan koma
            if (strpos($item->quantity, '.00') !== false) {
                $item->quantity = number_format($item->quantity, 0, ',', '.');
            } else {
                $item->quantity = number_format($item->quantity, 2, ',', '.');
            }
        }
        $date = Carbon::now()->format('d F Y');

        $widget = [
            'inventories' => $inventories,
            'date' => $date,
        ];

        $fileName = 'inventory' . time() . '.pdf';



        // dompdf
        $pdf = \PDF::loadView('inven.pdf', compact('widget'));
        // landscape
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('inventory.pdf');
    }
}
