@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="col-12">
        <div class="alert alert-danger"> <i class="ti-alert"></i> {{ $error }}.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        </div>
    </div>
    @endforeach
@endif