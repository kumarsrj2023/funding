<!--SEND SOP Modal -->
<div class="modal fade" data-sop-url="{{ route('businesses.director.info.ajax', $id) }}" id="sopModel" tabindex="-1" aria-labelledby="sopModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="sopModelLabel">Director Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card no-lt-rt-pad">
                    {!! Helper::getDatatablesForSOP(['Name', 'email', 'Address']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background-color: #1a335f">Close</button>
                <button type="button" class="btn text-white send-sop d-none" data-send-sop-url="{{ route('send.sop') }}" style="background-color: #1a335f">Send SOP</button>
            </div>
        </div>
    </div>
</div>




