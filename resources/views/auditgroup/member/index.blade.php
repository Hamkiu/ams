<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i data-feather="user-plus"></i>
                Anggota Audit Group
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
              {{-- optinal komen untuk pengarah --}}
              <div class="col-md-8 mb-4">
                <h5 class="mb-0">
                    Senarai Juruaudit
                </h5>
                <br/>
                <div class="table-responsive">
                    <table class="table table-bordered groupmember" id="groupMember" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sort</th>
                                <th>Juruaudit</th>                                        
                                <th>Jabatan / Unit</th>
                                <th>Peranan</th>
                                <th>Created By</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        
            <div class="col-md-4" style="background-color: #EBEBEB;">
                <hr/>
                <form role="form" id="auditGroupMemberForm" name="auditGroupMemberForm" method="post" action="{{ route('auditgroupmember.store', encode($auditGroup->id)) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label>Juruaudit</label>
                                <select name="user_id" class="form-control" id="user_id">
                                    <option value="">-- Pilih Juruaudit --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-jabatan="{{ $user->jabatan }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label>Jabatan / Unit</label>
                                <small class="text-muted">(Automatik)</small>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan / Unit" readonly>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12">
                            <div id="att1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-2">
                                            <label>Peranan</label>
                                            <select name="role" class="form-control">
                                                <option value="">-- Pilih Peranan --</option>
                                                <option value="Leader">Ketua Juruaudit</option>
                                                <option value="Member">Ahli Juruaudit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label>Catatan</label>
                                <small class="text-muted">(Optional)</small>
                                <textarea name="remarks" id="remarks" class="form-control" placeholder="Catatan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success addAttOpen" >Tambah Juruaudit</button>
                        </div>
                    </div>
                </form>
                <hr/>
            </div>
        </div>
    </div>
</div>