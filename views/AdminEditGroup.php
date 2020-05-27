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
[[ foreach ($permissions as $permission) : ]]
        <tr>
          <td class="align-middle">{{$permission -> name}}</td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@admin/remove_permission/{{$permission -> id}}@@')"><span class="material-icons">delete</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
    
    
  </div>
</div>
          
%% views/footer.html %% 