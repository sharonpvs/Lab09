<p class="lead">
    <a href="/order/neworder" class="btn btn-large btn-primary">Start a new order</a>    
    <a href="/admin" class="btn btn-large btn-danger">Secret Admin Stuff</a>
    <a href="/authenticate/logout" class="btn btn-large btn-inverse">Logout</a>
</p>
<p>Order summary:</p>
<table class="table">
    <tr>
        <th>Order #</th>
        <th>Date/Time</th>
        <th>Amount</th>
    </tr>
    {orders}
    <tr>
        <td>{num}</td>
        <td>{datetime}</td>
        <td>{amount}</td>
    </tr>
    {/orders}
</table>
