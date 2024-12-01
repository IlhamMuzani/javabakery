<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Member;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');

        if ($filter == 'new') {
            $members = Member::orderBy('created_at', 'desc')->get();
        } elseif ($filter == 'old') {
            $members = Member::orderBy('created_at', 'asc')->get();
        } else {
            $members = Member::all();
        }

        return view('admin.member.index', compact('members'));
    }

    public function create()
    {
        return view('admin/member.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_member' => '0',
                'qrcode_member' => '0',
          
            ],
            [
                'kode_member.required' => 'Masukkan nama lengkap',
                'qrcode_member.required' => 'Pilih gender',
         
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }

        if ($request->gambar_ktp) {
            $gambar = str_replace(' ', '', $request->gambar_ktp->getClientOriginalName());
            $namaGambar = 'gambar_ktp/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar_ktp->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = null;
        }

        $kode = $this->kode();
        $tanggal = Carbon::now()->format('Y-m-d');
        Member::create(array_merge(
            $request->all(),
            [
                'gambar_ktp' => $namaGambar,
                'kode_member' => $this->kode(),
                'qrcode_member' => 'https://javabakery.id/member/' . $kode,
                'tanggal_awal' => $tanggal,

            ]
            ));
        return redirect('admin/member')->with('success', 'Berhasil menambahkan member');
    }

    public function kode()
    {
        $member = Member::all();
        if ($member->isEmpty()) {
            $num = "000001";
        } else {
            $id = Member::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'JB';
        $kode_member = $data . $num;
        return $kode_member;
    }

    public function edit($id)
    {

        $member = Member::where('id', $id)->first();
        return view('admin/member.update', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_member' => 'required',
                'qrcode_member' => 'required',
               
            ],
            [
                'kode_member.required' => 'Masukkan nama lengkap',
                'qrcode_member.required' => 'Pilih gender',
               
              
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $member = Member::findOrFail($id);

        if ($request->gambar_ktp) {
            Storage::disk('local')->delete('public/uploads/' . $member->gambar_ktp);
            $gambar = str_replace(' ', '', $request->gambar_ktp->getClientOriginalName());
            $namaGambar = 'gambar_ktp/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar_ktp->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = $member->gambar_ktp;
        }

        Member::where('id', $id)->update([
            // 'gambar_ktp'=> $namaGambar,
            'nama_member' => $request->nama_member,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_gabung' => $request->tanggal_gabung,
            'gender' => $request->gender,
            // 'umur' => $request->umur,
            'telp' => $request->telp,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ]);

        return redirect('admin/member')->with('success', 'Berhasil memperbarui member');
    }


    public function cetakqrcode($id)
    {
        $members = Member::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.member.cetak_pdf', compact('members'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodemember.pdf');
    }

    public function show($id)
    {


        $member = Member::where('id', $id)->first();
        return view('admin/member.show', compact('member'));
    }


    public function destroy($id)
    {
        $tipe = Member::find($id);
        $tipe->delete();

        return redirect('admin/member')->with('success', 'Berhasil menghapus member');
    }
}