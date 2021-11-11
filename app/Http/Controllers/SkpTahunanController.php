<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkpTahunanHeader;
use App\User;
use App\Status;
use App\ValidationTemp;

use Illuminate\Support\Facades\Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

use App\Exports\SkpExport;
use Maatwebsite\Excel\Facades\Excel;

class SkpTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole(['Super-Admin', 'Administrator', 'Kepegawaian'])) {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
            ->get(['skp_tahunan_header.*', 'users.name']);
            $users = User::all()->pluck('name', 'id');

            $skps->map(function ($skp) {
                $status = Status::where('status', $skp->status)->first();
                if ($status) {
                    $skp['keterangan'] = $status->keterangan;
                } 
            });

            return view('skp.tahunan.index', compact('skps', 'users'));
        } else {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
                ->where('skp_tahunan_header.user_id', Auth::id())
                ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
                ->get(['skp_tahunan_header.*', 'users.name']);

            $skps->map(function ($skp) {
                $status = Status::where('status', $skp->status)->first();
                if ($status) {
                    $skp['keterangan'] = $status->keterangan;
                } 
            });

            return view('skp.tahunan.index')->with('skps', $skps);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'periode_mulai'=>'required',
                'periode_selesai'=>'required'
            ],
            $this->messages()
        );

        $request->merge(['status' => '01' ]);

        $skptahunanheader = SkpTahunanHeader::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'status'));

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan pembuatan SKP tahunan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $skp = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->where('skp_tahunan_header.id', $id)
            ->first(['skp_tahunan_header.*', 'users.name']);
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.edit', compact('skp', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $skp = SkpTahunanHeader::findOrFail($id);

        $this->validate($request, [
                'periode_mulai'=>'required',
                'periode_selesai'=>'required'
            ],
            $this->messages()
            
        );
        // $input = $request->only('periode_mulai', 'periode_selesai', 'user_id');
        // $skp->fill($input)->save();

        $skp->status = '04';
        $skp->save();

        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_header' ]);

        ValidationTemp::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'old_id', 'table_name'));

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan perubahan SKP tahunan.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skp = SkpTahunanHeader::findOrFail($id); 
        $skp->status = '07';
        $skp->save();

        $request = new Request();
        $request->merge(['periode_mulai' => $skp->periode_mulai ]);
        $request->merge(['periode_selesai' => $skp->periode_selesai]);
        $request->merge(['user_id' => $skp->user_id ]);
        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_header' ]);

        ValidationTemp::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'old_id', 'table_name'));
        // $skp->delete();

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan penghapusan SKP tahunan.');
    }

    public function messages()
    {
        return [
            'periode_mulai.required'=> 'Mohon isi kolom Periode Mulai',
            'periode_selesai.required'=> 'Mohon isi kolom Periode Selesai'            
        ];
    }

    public function export($id)
    {
        
        return Excel::download(new SkpExport($id), 'SKP.xlsx');
    }

    public function validate_data($id)
    {
        $skp = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->where('skp_tahunan_header.id', $id)
            ->first(['skp_tahunan_header.*', 'users.name']);
        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.validation', compact('skp', 'users', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $skp = SkpTahunanHeader::findOrFail($id);
                $skp->status = '02';
                $skp->save();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima SKP tahunan');
                break;
            case 'Decline':
                $skp = SkpTahunanHeader::findOrFail($id);
                $skp->status = '03';
                $skp->save();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak SKP tahunan.');
                break;
            case 'Confirm Update':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();

                $skp->periode_mulai = $validation->periode_mulai;
                $skp->periode_selesai = $validation->periode_selesai;
                $skp->user_id = $validation->user_id;
                $skp->status = '05';

                $skp->save();
                $validation->delete();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
                $skp->status = '06';

                $skp->save();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();

                $skp->delete();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
                $skp->status = '09';
                $skp->save();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
