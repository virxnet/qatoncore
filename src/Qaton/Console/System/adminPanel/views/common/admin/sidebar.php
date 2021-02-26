<?php 
function buildModelsList(array $models, string $base_url, string $path = '/')
{
    foreach ($models as $key => $model) {
        if (!is_array($model)) {
            $model = pathinfo($model)['filename'];
            echo "<a class='collapse-item' href='{$base_url}admin/panel/model/{$path}:{$model}'>{$model}</a>";
        }
    }
    foreach ($models as $key => $model) {
        if (is_array($model)) {
            $subpath = $path;
            if (!is_numeric($key)) {
                $subpath = str_replace('/', ':', $key);
                echo "<h6 class='collapse-header'>{$subpath}</h6>";
            }
            buildModelsList($model, $base_url, $subpath);
        }
    }
}
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php $this->baseUrl() ?>admin">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-hand-holding-water"></i>
    </div>
    <div class="sidebar-brand-text mx-3"><?php echo $title ?></sup>
        <small>VirX Qaton</small>
    </div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="<?php $this->baseUrl() ?>admin">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Heading -->
<div class="sidebar-heading">
    Keep Session Alive
</div>

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <span class="nav-link" href="<?php $this->baseUrl() ?>admin">
        <i class="fas fa-fw fa-user-clock"></i>
        <span id="keepAliveDisplay" class="badge badge-success">0</span>
        <span class="form-check-inline">
            <input type="radio" class="ml-1 form-check-input" id="keepAliveOn" name="keep_alive" value="1" checked><label class="form-check-label" for="keepAliveOn">On</label>
            <input type="radio" class="ml-1 form-check-input" id="keepAliveOff" name="keep_alive" value="0"><label class="form-check-label" for="keepAliveOff">Off</label>
        </span>
    </span>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Application
</div>

<!-- Nav Item - Models Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModels"
        aria-expanded="true" aria-controls="collapseModels">
        <i class="fas fa-fw fa-cog"></i>
        <span>Models</span>
    </a>

    <div id="collapseModels" class="collapse" aria-labelledby="headingModels" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
    
            <?php
            if (is_array($models) && !empty($models)) {
                buildModelsList($models, $this->base_url);
            }
            ?>

        </div>
    </div>
</li>

<?php /*

<!-- Nav Item - Controllers Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseControllers"
        aria-expanded="true" aria-controls="collapseControllers">
        <i class="fas fa-fw fa-cog"></i>
        <span>Controllers</span>
    </a>
    <div id="collapseControllers" class="collapse" aria-labelledby="headingControllers"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">App Controllers:</h6>
            <a class="collapse-item" href="Controllers-color.html">Colors</a>
            <a class="collapse-item" href="Controllers-border.html">Borders</a>
            <a class="collapse-item" href="Controllers-animation.html">Animations</a>
            <a class="collapse-item" href="Controllers-other.html">Other</a>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    System
</div>

<!-- Nav Item - Charts -->
<li class="nav-item">
    <a class="nav-link" href="charts.html">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Config</span></a>
</li>
*/ ?>

</ul>
<!-- End of Sidebar -->

