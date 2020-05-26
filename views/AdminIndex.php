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
  
  </div>
</div>
          
%% views/footer.html %% 