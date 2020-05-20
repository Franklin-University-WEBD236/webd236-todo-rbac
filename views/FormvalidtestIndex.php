%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    
    <form action="@@formvalidtest/index@@" method="post">
      <div class="form-group">
        <label for="required">required</label>
        <input type="text" min="1" id="required" name="form[required]" class="form-control" placeholder="Enter something" value="{{$this->value($form['required'])}}" />
      </div>
      <div class="form-group">
        <label for="phone">phone</label>
        <input type="text" min="1" id="phone" name="form[phone]" class="form-control" placeholder="Enter something" value="{{$this->value($form['phone'])}}" />
      </div>
      <div class="form-group">
        <label for="email">email</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter something" value="{{$this->value($form['email'])}}" />
      </div>
      <div class="form-group">
        <label for="integer">integer</label>
        <input type="text" min="1" id="integer" name="form[integer]" class="form-control" placeholder="Enter something" value="{{$this->value($form['integer'])}}" />
      </div>
      <div class="form-group">
        <label for="float">float</label>
        <input type="text" min="1" id="float" name="form[float]" class="form-control" placeholder="Enter something" value="{{$this->value($form['float'])}}" />
      </div>
      <div class="form-group">
        <label for="money">money</label>
        <input type="text" min="1" id="money" name="form[money]" class="form-control" placeholder="Enter something" value="{{$this->value($form['money'])}}" />
      </div>
      <div class="form-group">
        <label for="password">password</label>
        <input type="text" min="1" id="password" name="form[password]" class="form-control" placeholder="Enter something" value="{{$this->value($form['password'])}}" />
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('@@index@@')">Cancel</button>
      </div>
    </form>

  </div>
</div>
          
%% views/footer.html %% 