<?php
$sql = "select * from categories where parent=0";
$pquery = $db->query($sql);
?>
<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                <a href="index.php" class="navbar-brand">Fashion Store</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                        <a href="carts.php" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;</a>
                       
                     </form>
                <ul class="nav navbar-nav">
                    <?php while ($parent = mysqli_fetch_assoc($pquery)): ?>
                    <?php 
                    $parent_id = $parent['id']; 
                    $sql2 = "select * from categories where parent = '$parent_id'";
                    $cquery = $db->query($sql2);
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php while ($child = mysqli_fetch_assoc($cquery)):?>
                            <li class="text-left"><a href="category.php?cat=<?=$child['category_id'];?>"><?php echo $child['category'];?></a></li>
                            <?php endwhile;?>
                        </ul>
                    </li>
                    <?php endwhile;?>
                </ul>
                </div><!--end /.navbar-collapse-->
            </div><!--end container-->
        </nav><!--end navbar-->

