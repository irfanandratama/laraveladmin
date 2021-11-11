<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\TugasTambahan;
use App\SatuanKegiatan;
use App\User;
use App\Status;
use App\ValidationTemp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

class TugasTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $skpheader = SkpTahunanHeader::find($id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $user = User::find($skpheader->user_id);

        return view('skp.tahunan.tugas.create', compact('skpheader', 'tahun', 'user'));
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
                'tahun'=>'required',
                'nama_tugas'=>'required',
                'no_sk'=>'required'
            ],
            $this->messages()
        );

        $request->merge(['status' => '01' ]);

        $user = TugasTambahan::create($request->only('tahun', 'nama_tugas', 'no_sk', 'skp_tahunan_header_id', 'status'));

        return redirect()->route('tugas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
             'Tugas Tambahan successfully added.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $skpheader = SkpTahunanHeader::find($id);
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)
        ->whereIn('status', ['01', '02', '04', '05', '06', '07', '08', '09'])
        ->paginate(10);
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');

        $tugass->map(function ($tugas) {
            $status = Status::where('status', $tugas->status)->first();
            if ($status) {
                $tugas['keterangan'] = $status->keterangan;
            } 
        });
        
        return view('skp.tahunan.tugas.index', compact('skpheader', 'tugass', 'id', 'user', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tugas = TugasTambahan::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($tugas->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');

        return view('skp.tahunan.tugas.edit', compact('skpheader', 'tugas', 'id', 'user', 'tahun'));
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
        $tugas = TugasTambahan::findOrFail($id);

        $this->validate($request, [
                'tahun'=>'required',
                'nama_tugas'=>'required',
                'no_sk'=>'required'
            ],
            $this->messages()
        );

        // $input = $request->except('tahun');
        // $tugas->fill($input)->save();

        $request->merge(['old_id' => $tugas->id ]);
        $request->merge(['table_name' => 'tugas_tambahan' ]);

        ValidationTemp::create($request->except('tahun'));

        $tugas->status = '04';
        $tugas->save();

        return redirect()->route('tugas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Tugas Tambahan successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tugas = TugasTambahan::findOrFail($id); 
        $skpheaderid = $tugas->skp_tahunan_header_id;
        $tugas->status = '07';
        $tugas->save();
        // $tugas->delete();

        $request = new Request();
        $request->merge(['tahun' => $tugas->tahun ]);
        $request->merge(['nama_tugas' => $tugas->nama_tugas ]);
        $request->merge(['no_sk' => $tugas->no_sk ]);
        $request->merge(['old_id' => $tugas->id ]);
        $request->merge(['table_name' => 'tugas_tambahan' ]);

        ValidationTemp::create($request->only('tahun', 'nama_tugas', 'no_sk', 'old_id', 'table_name'));

        return redirect()->route('tugas.show', [$skpheaderid])
            ->with('flash_message',
             'Tugas Tambahan successfully deleted.');
    }

    public function messages()
    {
        return [
            'tahun.required'=> 'Mohon isi kolom Tahun',
            'nama_tugas.required'=> 'Mohon isi kolom Nama Tugas',
            'no_sk.required'=> 'Mohon mengisi kolom Nomor Surat Kuasa'      
        ];
    }

    public function validate_data($id)
    {
        $tugas = TugasTambahan::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($tugas->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'tugas_tambahan')->first();

        return view('skp.tahunan.tugas.validation', compact('skpheader', 'tugas', 'id', 'user', 'tahun', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $tugas = TugasTambahan::findOrFail($id);
                $skpheaderid = $tugas->skp_tahunan_header_id;
                $tugas->status = '02';
                $tugas->save();
                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima tugas tambahan SKP tahunan');
                break;
            case 'Decline':
                $tugas = TugasTambahan::findOrFail($id);
                $skpheaderid = $tugas->skp_tahunan_header_id;
                $tugas->status = '03';
                $tugas->save();
                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak target SKP tahunan.');
                break;
            case 'Confirm Update':
                $tugas = TugasTambahan::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'tugas_tambahan')->first();
                $skpheaderid = $tugas->skp_tahunan_header_id;

                $tugas->nama_tugas = $validation->nama_tugas;
                $tugas->no_sk = $validation->no_sk;
                $tugas->status = '05';

                $tugas->save();
                $validation->delete();
                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $tugas = TugasTambahan::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'tugas_tambahan')->first();
                $skpheaderid = $tugas->skp_tahunan_header_id;
                $tugas->status = '06';

                $tugas->save();
                $validation->delete();

                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $tugas = TugasTambahan::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'tugas_tambahan')->first();
                $skpheaderid = $tugas->skp_tahunan_header_id;

                $tugas->delete();
                $validation->delete();

                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $tugas = TugasTambahan::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'tugas_tambahan')->first();
                $skpheaderid = $tugas->skp_tahunan_header_id;

                $tugas->status = '09';
                $tugas->save();
                $validation->delete();

                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                $tugas = TugasTambahan::findOrFail($id);
                $skpheaderid = $tugas->skp_tahunan_header_id;
                return redirect()->route('tugas.show', [$skpheaderid])
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
