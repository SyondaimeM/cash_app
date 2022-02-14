<!-- Modal -->
<div class="modal fade" id="transaction" tabindex="-1" role="dialog" aria-labelledby="transactionTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="transactionTitle">Detail Transaction of <span id="nameModal"
                        style="color:maroon "></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionModalBoday">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
