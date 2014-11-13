<p class="lead">
    Our Menu List
        <a href="/admin/list2" class="btn btn-large btn-error">Show Editable Items!</a>

</p>
<table class="table">
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Description</th>
        <th>Picture</th>
    </tr>
    {items}
    <tr>
        <td>{code}</td>
        <td>{name}</td>
        <td>{description}</td>
        <td><img src="/assets/images/{picture}" width="32" height="32"/></td>
    </tr>
    {/items}
</table>
