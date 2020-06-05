%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>

    <h2>Members</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>User name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($members as $user) : ]]
        <tr>
          <td class="align-middle">{{$user -> getFullName()}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/remove_member/{{$group['id']}}/{{$user['id']}}@@')"><span class="material-icons">remove_circle</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  
    <h2>Non-members</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>User name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($non_members as $user) : ]]
        <tr>
          <td class="align-middle">{{$user -> getFullName()}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between" onclick="post('@@admin/add_member/{{$group['id']}}/{{$user['id']}}@@')"><span class="material-icons">add_circle</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  
  </div>
</div>
          
%% views/footer.html %% 