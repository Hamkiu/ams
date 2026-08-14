@extends('layouts.master')
@section('title', 'Senarai Audit')
@section('content')

    <div class="row">

        @forelse($groups as $member)
            <div class="col-lg-6">

                <div class="card">

                    <div class="card-body">

                        <h5>
                            <b>Nama Group :</b>
                            {{ $member->auditGroup->name }}

                        </h5>

                        <p>

                            <b>Template :</b>

                            {{ $member->auditGroup->auditTemplate->name }}

                        </p>
                        <p>

                            <b>Klausa :</b>

                            {{ $member->auditGroup->auditTemplate->klausa }}

                        </p>

                        <p>
                            <b>Tarikh Cadangan Audit:</b>
                            {{ date('d-m-Y', strtotime($member->auditGroup->tarikh)) }}
                        </p>
                        <p>
                            <b>Jabatan:</b>
                            {{ $member->auditGroup->jabatan }}
                        </p>
                        <p>
                            <b>Status :</b>

                            {{ $member->status }}

                        </p>
                        <p>
                            <b>Ketua Kumpulan :</b>

                            @php
                                $leader = $member->auditGroup->members->where('role', 'Leader')->first();
                            @endphp

                            {{ $leader?->pengguna?->name ?? '-' }}
                        </p>

                        <p>
                            <b>Ahli Kumpulan :</b>
                        </p>

                        <ul class="mb-3">

                            @foreach ($member->auditGroup->members as $groupMember)
                                <li class="mb-1">

                                    {{ $groupMember->pengguna->name }}

                                    {{-- Role --}}
                                    @if ($groupMember->role == 'Leader')
                                        <span class="badge bg-primary ms-2">
                                            Ketua
                                        </span>
                                    @endif

                                    {{-- Status --}}
                                    @if ($groupMember->status == 'SELESAI')
                                        <span class="badge bg-success ms-1">
                                            Selesai
                                        </span>
                                    @elseif ($groupMember->status == 'DALAM PROSES')
                                        <span class="badge bg-warning text-dark ms-1">
                                            Dalam Proses
                                        </span>
                                    @else
                                        <span class="badge bg-secondary ms-1">
                                            Belum Bermula
                                        </span>
                                    @endif

                                </li>
                            @endforeach

                        </ul>
                        @if ($member->status == 'SELESAI')

                            {{-- Button lihat jawapan sendiri --}}
                            <a href="{{ route('audit.show', encode($member->auditGroup->id)) }}" class="btn btn-secondary">
                                Lihat Audit
                            </a>


                            {{-- Hanya Ketua Juruaudit --}}
                            @if ($member->role == 'Leader')
                                {{-- Belum hantar rumusan --}}
                                @if ($member->auditGroup->status == 'MENUNGGU KESIMPULAN')
                                    <a href="{{ route('audit.summary', encode($member->auditGroup->id)) }}"
                                        class="btn btn-success">

                                        <i class="material-icons-outlined align-middle" style="font-size: 18px;">
                                            note_add
                                        </i>

                                        @if ($member->auditGroup->conclusion)
                                            Kemaskini Rumusan
                                        @else
                                            Tambah Rumusan
                                        @endif

                                    </a>

                                    {{-- Rumusan sudah dihantar --}}
                                @elseif ($member->auditGroup->status == 'MENUNGGU ULASAN')
                                    <a href="{{ route('audit.summary', encode($member->auditGroup->id)) }}"
                                        class="btn btn-info">

                                        <i class="material-icons-outlined align-middle" style="font-size: 18px;">
                                            visibility
                                        </i>

                                        Lihat Rumusan

                                    </a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('audit.show', encode($member->auditGroup->id)) }}" class="btn btn-primary">
                                Buka Audit
                            </a>
                        @endif
                    </div>

                </div>

            </div>

        @empty

            <div class="col-md-12">

                <div class="alert alert-warning">

                    Tiada audit diberikan.

                </div>

            </div>
        @endforelse

    </div>

@endsection
