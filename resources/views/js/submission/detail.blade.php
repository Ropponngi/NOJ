@include("js.common.hljs")
<style>
.modal-dialog-submission {
    width: 60vw;
}

.modal-dialog-submission .modal-content{
    min-width: 50vw;
}

.modal-dialog-submission .modal-body{
    word-break: break-word;
}
.modal-dialog-submission .table tbody tr:hover{
    background:transparent;
}
</style>
<script>
    var fetchingSubmission=false;
    function fetchSubmissionDetail(sid){
        if(fetchingSubmission) return;
        fetchingSubmission=true;
        $.ajax({
            type: 'POST',
            url: '/ajax/submission/detail',
            data: {
                sid: sid,
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(ret) {
                console.log(ret);
                if(ret.ret==200){
                    var id = new Date().getTime();
                    $('body').append(`
                    <div class="modal fade" id="submission${id}" data-backdrop="static" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-submission" role="document">
                            <div class="modal-content sm-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="MDI script"></i> Submission Detail</h5>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-reflow">
                                        <thead>
                                            <tr>
                                                <th scope="col">Status</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Memory</th>
                                                <th scope="col">Lang</th>
                                                <th scope="col">Submitted</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="${ret.data.color}">${ret.data.verdict}</td>
                                                <td>${ret.data.time}ms</td>
                                                <td>${ret.data.memory}kb</td>
                                                <td>${ret.data.language}</td>
                                                <td>${new Date(ret.data.submission_date * 1000).toLocaleString()}</td>
                                            </tr>
                                        </tbody>
                                    </table>
<pre class="${ret.data.lang}" style="padding:1rem;border-radius:4px;margin-bottom:0;margin-top:1rem;">
</pre>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);
                    $(`#submission${id}`).on('shown.bs.modal', function (e) {
                        changeDepth();
                    });
                    $(`#submission${id}`).modal('toggle');
                    if(ret.data.solution!==null) {
                        $(`#submission${id} pre`).text(ret.data.solution);
                        hljs.highlightBlock(document.querySelector(`#submission${id} pre`));
                    }else{
                        $(`#submission${id} pre`).remove();
                    }
                } else {
                    alert(ret.desc);
                }
                fetchingSubmission=false;
            }, error: function(xhr, type) {
                console.log('Ajax error while posting to submitHistory!');
                fetchingSubmission=false;
            }
        });
    }
</script>
