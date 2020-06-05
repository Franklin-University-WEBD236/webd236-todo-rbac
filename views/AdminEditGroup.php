%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    <form action="@@admin/edit_group/{{$form['id']}}@@" method="post">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="name">Group name</label>
            <input type="text" min="1" id="name" name="form[name]" class="form-control" placeholder="Enter name" value="{{$this->value($form['name'])}}" />
          </div>
        </div>
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('@@index@@')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-12">
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Assigned permissions for {{$this->value($form['name'])}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

[[ foreach ($permissions as $permission) : ]]
        <tr>
          <td class="align-middle">{{$permission -> name}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/remove_permission/{{$form['id']}}/{{$permission['id']}}@@')"><span class="material-icons">remove_circle</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-12">
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Unassigned permissions for {{$this->value($form['name'])}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

[[ foreach ($available_permissions as $permission) : ]]
        <tr>
          <td class="align-middle">{{$permission -> name}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between" onclick="post('@@admin/add_permission/{{$form['id']}}/{{$permission['id']}}@@')"><span class="material-icons">add_circle</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  </div>
</div>
          
%% views/footer.html %% 