<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\Kreativitas;
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

class KreativitasController extends Controller
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
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.kreativitas.create', compact('skpheader', 'tahun', 'user', 'satuankegiatan'));

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
            'tanggal_kreativitas'=>'required',
            'kegiatan_kreativitas'=>'required',
            'kuantitas'=>'required',
            'satuan_kegiatan_id' => 'required'
        ],
        $this->messages()
    );

    $request->merge(['status' => '01' ]);

    $user = Kreativitas::create($request->only('tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas', 'satuan_kegiatan_id', 'skp_tahunan_header_id', 'status'));

    return redirect()->route('kreativitas.show', [$request->skp_tahunan_header_id])
        ->with('flash_message',
         'Kreativitas successfully added.');
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
        $kreatifs = Kreativitas::where('skp_tahunan_header_id', $id)
        ->whereIn('status', ['01', '02', '04', '05', '06', '07', '08', '09'])
        ->paginate(10);
        $user = User::find($skpheader->user_id);
        $satuan = SatuanKegiatan::all();
        $users = User::all()->pluck('name', 'id');

        $kreatifs->map(function ($kreatif) {
            $status = Status::where('status', $kreatif->status)->first();
            if ($status) {
                $kreatif['keterangan'] = $status->keterangan;
            } 
        });
        
        return view('skp.tahunan.kreativitas.index', compact('skpheader', 'kreatifs', 'id', 'user', 'satuan', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kreatif = Kreativitas::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($kreatif->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.kreativitas.edit', compact('skpheader', 'kreatif', 'id', 'user', 'tahun', 'satuankegiatan'));

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
        $tugas = Kreativitas::findOrFail($id);

        $this->validate($request, [
                'tanggal_kreativitas'=>'required',
                'kegiatan_kreativitas'=>'required',
                'kuantitas'=>'required',
                'satuan_kegiatan_id' => 'required'
            ],
            $this->messages()
        );

        $request->merge(['old_id' => $tugas->id ]);
        $request->merge(['table_name' => 'kreativitas' ]);

        ValidationTemp::create($request->except('tahun'));

        $tugas->status = '04';
        $tugas->save();

        return redirect()->route('kreativitas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kreativitas successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kreatif = Kreativitas::findOrFail($id); 
        $skpheaderid = $kreatif->skp_tahunan_header_id;
        $kreatif->status = '07';
        $kreatif->save();

        $request = new Request();
        $request->merge(['tanggal_kreativitas' => $kreatif->tanggal_kreativitas ]);
        $request->merge(['kegiatan_kreativitas' => $kreatif->kegiatan_kreativitas ]);
        $request->merge(['kuantitas' => $kreatif->kuantitas ]);
        $request->merge(['satuan_kegiatan_id' => $kreatif->satuan_kegiatan_id ]);
        $request->merge(['old_id' => $kreatif->id ]);
        $request->merge(['table_name' => 'kreativitas' ]);

        ValidationTemp::create($request->only('tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas', 'satuan_kegiatan_id', 'old_id', 'table_name'));

        return redirect()->route('kreativitas.show', [$skpheaderid])
            ->with('flash_message',
             'Tugas Tambahan successfully deleted.');
    }

    public function messages()
    {
        return [
            'tanggal_kreativitas.required'=> 'Mohon isi kolom Tanggal',
            'kegiatan_kreativitas.required'=> 'Mohon isi kolom Kegiatan Kreativitas',
            'kuantitas.required'=> 'Mohon mengisi kolom Kuantitas', 
            'satuan_kegiatan_id.required'=> 'Mohon pilih satuan kuantitas yang sesuai',    
        ];
    }

    public function validate_data($id)
    {
        $kreatif = Kreativitas::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($kreatif->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');
        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'kreativitas')->first();

        return view('skp.tahunan.kreativitas.validation', compact('skpheader', 'kreatif', 'id', 'user', 'tahun', 'satuankegiatan', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $kreatif = Kreativitas::findOrFail($id);
                $skpheaderid = $kreatif->skp_tahunan_header_id;
                $kreatif->status = '02';
                $kreatif->save();
                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima tugas tambahan SKP tahunan');
                break;
            case 'Decline':
                $kreatif = Kreativitas::findOrFail($id);
                $skpheaderid = $kreatif->skp_tahunan_header_id;
                $kreatif->status = '03';
                $kreatif->save();
                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak target SKP tahunan.');
                break;
            case 'Confirm Update':
                $kreatif = Kreativitas::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'kreativitas')->first();
                $skpheaderid = $kreatif->skp_tahunan_header_id;
                $kreatif->tanggal_kreativitas = $validation->tanggal_kreativitas;
                $kreatif->kegiatan_kreativitas = $validation->kegiatan_kreativitas;
                $kreatif->kuantitas = $validation->kuantitas;
                $kreatif->satuan_kegiatan_id = $validation->satuan_kegiatan_id;
                $kreatif->status = '05';

                $kreatif->save();
                $validation->delete();
                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $kreatif = Kreativitas::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'kreativitas')->first();
                $skpheaderid = $kreatif->skp_tahunan_header_id;
                $kreatif->status = '06';

                $kreatif->save();
                $validation->delete();

                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $kreatif = Kreativitas::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'kreativitas')->first();
                $skpheaderid = $kreatif->skp_tahunan_header_id;

                $kreatif->delete();
                $validation->delete();

                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $kreatif = Kreativitas::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'kreativitas')->first();
                $skpheaderid = $kreatif->skp_tahunan_header_id;

                $kreatif->status = '09';
                $kreatif->save();
                $validation->delete();

                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                $kreatif = Kreativitas::findOrFail($id);
                $skpheaderid = $kreatif->skp_tahunan_header_id;
                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    } 
}
