<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <a href="/fashion_store/admin_area/index.php" class="navbar-brand">Fashion Store Admin</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                     </form>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">DashBoard</a></li>
                    <li><a href="brands.php">Brand</a></li>
                    <li><a href="category.php">Category</a></li>
                    <li><a href="products.php">Product</a></li>
                    <li><a href="archive.php">Archive</a></li>
                    <?php if (has_permission('admin')):?>
                    <li><a href="users.php">User</a></li>
                    <?php endif;?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>!
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="change_password.php">Change Password</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">                            
                            <li class="text-left"><a href="#"></a></li>                            
                        </ul>
                    </li>-->                   
                </ul>
                </div><!--end /.navbar-collapse-->
            </div><!--end container-->
        </nav><!--end navbar-->



