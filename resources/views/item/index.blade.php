@extends('layouts.master')
@section('title', 'Senarai Item')
@section('content')
@include('include.error')
<div class="accordion" id="toggleAccordion">
    <div class="card">
        <div class="card-header" id="head1">
            <section class="mb-0 mt-0">
                <div role="menu" class="collapsed d-flex justify-content-center align-items-center" data-bs-toggle="collapse" data-bs-target="#defaultAccordionOne" aria-expanded="false" aria-controls="defaultAccordionOne">
                    <i data-feather="info"></i>&nbsp;Maklumat Template {{ $auditTemplate->id }}
                </div>
            </section>
        </div>

        <div id="defaultAccordionOne" class="collapse" aria-labelledby="head1" data-bs-parent="#toggleAccordion">

            <div class="card-body">
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group mb-3">
                            <label>Nama Template</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $auditTemplate->name) }}" disabled>
                        </div>
                    </div>
        
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label>No Rujukan</label>
                            <input type="text"
                                   name="no_rujukan"
                                   class="form-control"
                                   value="{{ old('no_rujukan', $auditTemplate->no_rujukan) }}" disabled>
                        </div>
                    </div>
        
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label>No Pindaan</label>
                            <input type="number"
                                   name="no_pindaan"
                                   class="form-control"
                                   min="0"
                                   step="1"
                                   value="{{ old('no_pindaan', $auditTemplate->no_pindaan) }}" disabled>
                        </div>
                    </div>
        
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label>Version</label>
                            <input type="text"
                                   name="version"
                                   class="form-control"
                                   value="{{ old('version', $auditTemplate->version) }}" disabled>
                        </div>
                    </div>
        
                </div>
        
                <div class="row">
        
                    <div class="col-md-10">
                        <div class="form-group mb-3">
                            <label>Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3" disabled>{{ old('description', $auditTemplate->description) }}</textarea>
                        </div>
                    </div>
        
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label>Tarikh Berkuatkuasa</label>
                            <input type="date"
                                   name="tarikh_berkuatkuasa"
                                   class="form-control"
                                   value="{{ old('tarikh_berkuatkuasa', optional($auditTemplate->tarikh_berkuatkuasa)->format('Y-m-d')) }}" disabled>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    </div>
    {{-- tamat accordian 1 --}}
    {{-- accordian 2 --}}
    @include('item.create')
    {{-- tamat accordian 2 --}}

    {{-- accordian 3 --}}
    @include('item.index2')
    {{-- tamat accordian 23 --}}
</div>
@endsection
@push('modal')
    <div class="modal fade" id="aMd1" tabindex="-1" role="dialog" aria-labelledby="aMdl" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="aMd1_content">
                
            </div>
        </div>
    </div>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        $('#itemTable').DataTable({

            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],

            ajax: {
                url: "{{ route('audittemplate.items.list', encode($auditTemplate->id)) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', width: '2%'},
                {
                    data: 'sort',
                    name: 'sort',
                    width: '2%'
                },
                {
                    data: 'perkara',
                    name: 'perkara'
                },
                {
                    data: 'no_klausa',
                    name: 'no_klausa',
                    width: '2%'
                },
                {
                    data: 'klausa',
                    name: 'klausa'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    className: 'text-center',
                    width: '6%'
                },
                {
                    data: 'created_by',
                    name: 'created_by'
                },
                {
                    data: 'updated_by',
                    name: 'updated_by'
                },
                {
                    data: 'tindakan',
                    name: 'tindakan',
                    orderable: false,
                    searchable: false
                }
            ],

        });

        $('#defaultAccordionThree').on('shown.bs.collapse', function () {
            itemTable.columns.adjust().draw();
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: true
            });        
        @endif

        $('body').on('click','.editItem', function (e){
            var id = $(this).data("id");
            var url = '{{ route("audittemplate.items.edit", ":id") }}';
            var new_url = url.replace(':id', id);
            $.ajax({
                url: new_url,
                type:'GET',
                success: function(data) {
                    $('#aMd1_content').html(data);
                    $('#aMd1').modal('show');
                }
            });
        });
    });
</script>
@endpush