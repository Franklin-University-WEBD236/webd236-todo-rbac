%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    
    <h2>Member of</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Group name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($member_of as $group) : ]]
        <tr>
          <td class="align-middle">{{$group -> name}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/remove_member/{{$group['id']}}/{{$user['id']}}@@')"><span class="material-icons">remove_circle</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>

    <h2>Not member of</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Group name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($not_member_of as $group) : ]]
        <tr>
          <td class="align-middle">{{$group -> name}}</td>
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