<div class="card mb-5 p-3">
    <b>Welcome <?=session('name')?> !</b>
    <div class="row mt-3 mb-3">
        <div class="col-lg-6">
            <a href="/account/myorder">
                <div class="card">
                    <div class="card-header">
                        <p>Total Orders</p>
                    </div>
                    <div class="card-body">
                        <p>2</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6">
            <a href="/account/wishlist">
                <div class="card">
                    <div class="card-header">
                        <p>Total Wishlist</p>
                    </div>
                    <div class="card-body">
                        <p>1</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <h5 class="recent">Recent Order</h5>
    <div class="table-responsive custom-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Price</th>
                    <th>Order Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>TCG-0000038</td>
                    <td>$ 76.32</td>
                    <td>Apr 22, 2025</td>
                    <td><a href="/account/myorder/38"><u>Details</u></a></td>
                </tr>
                <tr>
                    <td>TCG-0000037</td>
                    <td>$ 15.90</td>
                    <td>Apr 21, 2025</td>
                    <td><a href="/account/myorder/37"><u>Details</u></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>