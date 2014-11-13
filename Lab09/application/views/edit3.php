<!-- form to edit a menu item -->
<form action="/admin/post3/{code}" method="post">
    Item Code: <input type="text" name="code" id="code" value="{code}" disabled="disabled"/><br/>
    Name: <input type="text" name="name" id="name" value="{name}"/><br/>
    Description: <textarea id="description" name="description">{description}</textarea><br/>
    Price: <input type="text" name="price" id="name" value="{price}"/><br/>
    Category: <select name="category" id="category" >
        <option value="m">Meal</option>
        <option value="d">Drink</option>
        <option value="s">Sweet</option>
    </select><br/>
    Picture: <img src="/assets/images/{picture}"/><br/>
    <input type="submit" value="Submit changes"/>
</form>
