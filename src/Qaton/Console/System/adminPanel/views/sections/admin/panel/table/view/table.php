
<h4>Data</h4>
<table class="table table-striped table-bordered table-hover">
    <?php foreach ($data as $column => $value) : ?>
        <tr>
            <th class="text-right" style="width: 10%;">
                <?php echo $column ?>
            </th>
            <td>
                <?php if (isset($schema[$column]['type']) && $schema[$column]['type'] === 'foreign'): ?>
                    <?php if (isset($schema[$column]['foreign']) && isset($schema[$column]['key']) && is_array($value)) : ?>
                        <table class="table table-striped table-bordered table-hover table-responsive">
                        <?php foreach ($value as $foreign_column => $foreign_value) : ?>
                            <tr>
                                <th class="text-right">
                                    <?php echo $foreign_column ?>
                                </th>
                                <td>
                                    <?php echo $foreign_value ?> 
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <?php echo $value ?> [Foreign] 
                    <?php endif; ?>
                <?php else: ?>
                    <?php 
                    if (isset($schema[$column])) {
                        switch ($schema[$column]['type']) {
                            case 'string':
                            case 'int':
                            case 'integer':
                            case 'float':
                            case 'double':
                                echo $value;
                                break;
                            case 'text':
                                echo "<textarea class='form-control editor_view_only' type='text' name='{$column}'>{$value}</textarea>";
                                break;
                            case 'hashed':
                                echo "********";
                                break;
                            default:
                                echo $value;
                        }
                    } else {
                        echo $value;
                    }
                    ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="<?php $this->baseUrl() ?>admin/panel/table/edit/<?php echo $model_slug ?>/<?php echo $data['id'] ?>" class="btn btn-sm btn-primary btn-icon-split">
    <span class="icon text-white-50">
        <i class="fas fa-pencil-alt"></i>
    </span>
    <span class="text">Edit</span>
</a>
<a onclick="javascript: return confirm('Are you sure you want to clone this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/clone/<?php echo $model_slug ?>/<?php echo $data['id'] ?>" class="btn btn-sm btn-secondary btn-icon-split">
    <span class="icon text-white-50">
        <i class="fas fa-clone"></i>
    </span>
    <span class="text">Clone</span>
</a>
<a onclick="javascript: return confirm('Are you sure you want to trash this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/delete/<?php echo $model_slug ?>/<?php echo $data['id'] ?>" class="btn btn-sm btn-warning btn-icon-split">
    <span class="icon text-white-50">
        <i class="fas fa-trash"></i>
    </span>
    <span class="text">Trash</span>
</a>
<a onclick="javascript: return confirm('Are you sure you want to permanently delete this record?');" href="<?php $this->baseUrl() ?>admin/panel/table/purge/<?php echo $model_slug ?>/<?php echo $data['id'] ?>" class="btn btn-sm btn-danger btn-icon-split">
    <span class="icon text-white-50">
        <i class="fas fa-ban"></i>
    </span>
    <span class="text">Purge</span>
</a>

<a href="<?php $this->baseUrl() ?>admin/panel/model/<?php echo $model_slug ?>" class="btn btn-sm btn-success btn-icon-split float-right">
    <span class="icon text-white-50">
        <i class="fas fa-backward"></i>
    </span>
    <span class="text">Back</span>
</a>

<hr>

<h4>Schema</h4>
<table class="table table-striped table-bordered table-hover table-responsive">
    <?php foreach ($schema as $column => $properties) : ?>
        <tr>
            <th class="text-right">
                <?php echo $column ?>
            </th>
            <td>
                <?php
                    foreach ($properties as $prop => $val) {
                        echo "<b>{$prop}:</b> ";
                        if (is_bool($val)) {
                            if ($val === true) {
                                echo 'true';
                            } else {
                                echo 'false';
                            }
                        } else {
                            echo $val;
                        }
                        echo "<br>";
                    }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

