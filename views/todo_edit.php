%% views/header.html %%

<div class="row">
  <div class="col-lg-12">

    <form action="@@todo/edit/{{$todo['id']}}@@" method="post">
      <div class="form-group">
        <label for="description">Make your changes below</label>
        <input type="text" min="1" id="description" name="form[description]" class="form-control" placeholder="Enter description" value="{{$todo['description']}}" />
        <input type="hidden" id="done" name="form[done]" value="{{$todo['done']}}" />
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="return get('@@index@@')">Cancel</button>
      </div>
    </form>
  </div>
</div>

%% views/footer.html %%
