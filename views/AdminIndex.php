%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    
    <h2>Users</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>User name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($users as $user) : ]]
        <tr>
          <td class="align-middle">{{$user -> getFullName()}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between mr-1" onclick="get('@@admin/edit_user_groups/{{$user -> id}}@@')"><span class="material-icons">group</span></button>
              <button class="btn btn-primary d-flex justify-content-center align-content-between mr-1" onclick="get('@@admin/edit_user/{{$user -> id}}@@')"><span class="material-icons">create</span></button>
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/delete_user/{{$user -> id}}@@')"><span class="material-icons">delete</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  
    <h2>Groups</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Group name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($groups as $group) : ]]
        <tr>
          <td class="align-middle"><?php echo "{$group -> getName()}" ?></td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between mr-1" onclick="get('@@admin/edit_group_members/{{$group -> id}}@@')"><span class="material-icons">group</span></button>
              <button class="btn btn-primary d-flex justify-content-center align-content-between mr-1" onclick="get('@@admin/edit_group/{{$group -> id}}@@')"><span class="material-icons">create</span></button>
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/delete_group/{{$group -> id}}@@')"><span class="material-icons">delete</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>

    <form action="@@admin/add_group@@" method="post">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="name">Add a group</label>
            <input type="text" min="1" id="name" name="form[name]" class="form-control" placeholder="Enter name" value="{{$this->value($form['name'])}}" />
          </div>
        </div>
      </div>
      <div class="form-group mt-2">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
    
  </div>
</div>
          
%% views/footer.html %% 