<div class="card mt-2">
    <div class="card-header" id="head3">
        <section class="mb-0 mt-0">
            <div role="menu" class="collapsed d-flex justify-content-center align-items-center" data-bs-toggle="collapse" data-bs-target="#defaultAccordionThree" aria-expanded="false" aria-controls="defaultAccordionThree">
                <i data-feather="list"></i>&nbsp;Senarai Item
            </div>
        </section>
    </div>
    <div id="defaultAccordionThree" class="collapse show" aria-labelledby="head3" data-bs-parent="#toggleAccordion">
        <div class="card-body">
            <div class="table-responsive">
                <table id="itemTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Bil</th>
                            <th>Sort</th>
                            <th>Perkara</th>
                            <th>No Klausa</th>
                            <th>Klausa</th>
                            <th>Status</th>
                            <th>Dicipta Oleh</th>
                            <th>Dikemaskini Oleh</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('audittemplate') }}" class="btn btn-secondary float-end">Kembali</a>
        </div>
    </div>
</div>