<?php
class AdminController extends Controller {

  public function __construct() {
    parent::__construct();
    // this protects the entire controller
  }

  public function get_index() {
    $this -> auth -> ensure('admin_page');
    $users = UserModel::findAll();
    $groups = GroupModel::findAll();
    $this->view->renderTemplate(
      "views/AdminIndex.php",
      array(
        'title' => 'Administrative interface',
        'users' => $users,
        'groups' => $groups,
      )
    );
  }

  public function get_edit_user($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    $this->view->renderTemplate(
      "views/AdminEditUser.php",
      array(
        'title' => 'Edit user',
        'user' => $user,
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }

  public function post_edit_user($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    $form = safeParam($_POST, 'form');
    $this->validator->email('email1', safeParam($form, 'email1'), "Invalid email address given.");
    $this->validator->same('email2', safeParam($form, 'email1'), safeParam($form, 'email2'), "Email addresses must match.");
    if ($this->validator->hasErrors()) {
      $this->view->renderTemplate(
        "views/AdminEditUser.php",
        array(
          'title' => 'Edit user',
          'user' => $user,
          'errors' => $this->validator->allErrors(),
          'form' => array(
            'firstName' => $form['firstName'],
            'lastName'  => $form['lastName'],
            'email1'    => $form['email1'],
            'email2'    => $form['email2'],
          )
        )
      );
    }
    $user->firstName=$form['firstName'];
    $user->lastName=$form['lastName'];
    $user->email=$form['email1'];
    $user->update();
    $this->view->flash("User updated");
    $this->view->redirectRelative("admin");
  }

  public function post_edit_password($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die("No data submitted.");
    }
    $this->validator->password('newPassword1', safeParam($form, 'newPassword1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $this->validator->same('newPassword2', safeParam($form, 'newPassword1'), safeParam($form, 'newPassword2'), "Passwords must match.");
    if ($this->validator->hasErrors()) {
      $this->view->renderTemplate(
        "views/AdminEditUser.php",
        array(
          'title' => 'Edit user',
          'user' => $user,
          'errors' => $this->validator->allErrors(),
          'form' => array(
            'firstName' => $user['firstName'],
            'lastName'  => $user['lastName'],
            'email1'    => $user['email'],
            'email2'    => $user['email'],
            'newPassword1' => safeParam($form, 'newPassword1'),
            'newPassword2' => safeParam($form, 'newPassword2'),
          )
        )
      );
    }
    $user->password = password_hash(safeParam($form, 'newPassword1'), PASSWORD_DEFAULT);
    $user->update();
    $this->view->flash("User password updated");
    $this->view->redirectRelative("admin");
  }

  public function post_delete_user($id) {
    $this -> auth -> ensure('delete_user');
    $user = UserModel::findById($id);
    $user->delete();
    $this->view->flash("User deleted");
    $this->view->redirectRelative("admin");
  }

  public function get_edit_group($id) {
    $group = GroupModel::findById($id);
    $permissions = $group -> getPermissions();
    $available_permissions = $group -> getNonPermissions();
    $this->view->renderTemplate(
      "views/AdminEditGroup.php",
      array(
        'title' => 'Edit group',
        'form' => $group -> toArray(),
        'permissions' => $permissions,
        'available_permissions' => $available_permissions,
      )
    );
  }

  public function post_edit_group($id) {
    $form = safeParam($_POST, 'form');
    $group = GroupModel::findById($id);
    $this -> validator -> required('name', safeParam($form, 'name'));
    if ($this->validator->hasErrors()) {
      $permissions = $group -> getPermissions();
      $available_permissions = $group -> getNonPermissions();
      $this->view->renderTemplate(
        "views/AdminEditGroup.php",
        array(
          'title' => 'Edit group',
          'form' => $group -> toArray(),
          'permissions' => $permissions,
          'available_permissions' => $available_permissions,
          'errors' => $this->validator->allErrors(),
        )
      );
    }
    $this->view->flash("Group name updated");
    $group -> name = trim(safeParam($form, 'name'));
    $group -> update();
    $this->view->redirectRelative("admin");
  }

  public function post_add_permission($group_id, $perm_id) {
    $permission = PermissionModel::findById($perm_id);
    $group = GroupModel::findById($group_id);
    $group -> addPermission($permission);
    $this->view->flash("Permission added");
    $this->view->redirectRelative("admin/edit_group/$group_id");
  }

  public function post_remove_permission($group_id, $perm_id) {
    $permission = PermissionModel::findById($perm_id);
    $group = GroupModel::findById($group_id);
    $group -> removePermission($permission);
    $this->view->flash("Permission removed");
    $this->view->redirectRelative("admin/edit_group/$group_id");
  }

  public function post_delete_group($id) {
    $this -> auth -> ensure('delete_group');
    $group = GroupModel::findById($id);
    $group->delete();
    $this->view->flash("Group deleted");
    $this->view->redirectRelative("admin");
  }

  public function post_add_group() {
    $this -> auth -> ensure('add_group');
    $form = safeParam($_POST, 'form');
    $this -> validator -> required('name', safeParam($form, 'name'));
    if ($this->validator->hasErrors()) {
      $this->view->flash("Invalid group name", "alert-danger");
      $this->view->redirectRelative("admin");
    }
    $this->view->flash("Group created");
    $group = new GroupModel($form);
    $group -> insert();
    $this->view->redirectRelative("admin");
  }

  public function get_edit_group_members($id) {
    $group = GroupModel::findById($id);
    $members = $group -> getMembers();
    $non_members = $group -> getNonMembers();
    $_SESSION['return_to'] = "admin/edit_group_members/$id";
    $this->view->renderTemplate(
      "views/AdminEditGroupMembers.php",
      array(
        'title' => "Membership of {$group->name}",
        'members' => $members,
        'non_members' => $non_members,
        'group' => $group,
      )
    );
  }

  public function post_add_member($group_id, $user_id) {
    $group = GroupModel::findById($group_id);
    $user = UserModel::findById($user_id);
    $group -> addUser($user);
    $this->view->flash("User added");
    $location = $_SESSION['return_to'];
    unset($_SESSION['return_to']);
    $this->view->redirectRelative($location);
  }

  public function post_remove_member($group_id, $user_id) {
    $group = GroupModel::findById($group_id);
    $user = UserModel::findById($user_id);
    $group -> removeUser($user);
    $this->view->flash("User removed");
    $location = $_SESSION['return_to'];
    unset($_SESSION['return_to']);
    $this->view->redirectRelative($location);
  }

  public function get_edit_user_groups($id) {
    $user = UserModel::findById($id);
    $member_of = GroupModel::findByUserId($id);
    $not_member_of = GroupModel::findByNotUserId($id);
    $_SESSION['return_to'] = "admin/edit_user_groups/$id";
    $this->view->renderTemplate(
      "views/AdminEditUserGroups.php",
      array(
        'title' => "{$user->getFullName()} Memberships",
        'user' => $user,
        'member_of' => $member_of,
        'not_member_of' => $not_member_of,
      )
    );
  }
}