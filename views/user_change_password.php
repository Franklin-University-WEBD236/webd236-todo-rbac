%% views/header.html %%

<div class="row">
  <div class="col-lg-12">

    <form action="@@user/change_password/{{$id}}@@" method="post">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="currentPassword">Current password</label>
            <input type="password" min="1" id="currentPassword" name="form[currentPassword]" class="form-control" placeholder="Enter current password" value="{{$this->value($form['currentPassword'])}}" />
          </div>
        </div>
        <div class="row mt-4">
          <div class="col">
            <label for="newPassword1">New password</label>
            <input type="password" min="1" id="newPassword1" name="form[newPassword1]" class="form-control" placeholder="Enter new password" value="{{$this->value($form['newPassword1'])}}" />
          </div>
          <div class="col">
            <label for="newPassword2">Verify new password</label>
            <input type="password" min="1" id="newPassword2" name="form[newPassword2]" class="form-control" placeholder="Re-enter new password" value="{{$this->value($form['newPassword2'])}}" />
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

%% views/footer.html %%
