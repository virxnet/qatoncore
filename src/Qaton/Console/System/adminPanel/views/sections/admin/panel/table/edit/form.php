
<h4>Data</h4>
<form method="post" action="<?php $this->baseUrl() ?>admin/panel/table/update/<?php echo $model_slug ?>/<?php echo $data['id'] ?>">
<table class="table table-striped table-bordered table-hover ">
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
                        <?php 
                        if (isset($foreigners[$column])) {
                            echo "<select class='form-control' name='{$column}'>";
                            foreach ($foreigners[$column] as $foreigner) {
                                echo "<option value='{$foreigner['id']}'>{$foreigner['id']}: ";
                                $foreigner_desc = '';
                                foreach ($foreigner as $f_key => $f_val) {
                                    if (is_string($f_val) && mb_strlen($f_val) < 100)
                                    {
                                        $foreigner_desc .= " {$f_val} ";
                                    }
                                    if (mb_strlen($foreigner_desc) > 100)
                                    {
                                        break;
                                    }
                                }
                                $foreigner_desc = mb_substr($foreigner_desc, 0, 100) . ' ...';
                                echo "{$foreigner_desc} </option>";
                            };
                            echo "</select>";
                        } else {
                            echo "<input class='form-control' type='text' name='{$column}' value='{$value[$schema[$column]['key']]}'>";
                        }
                        ?>
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
                                echo "<input class='form-control' type='text' name='{$column}' value='{$value}'>";
                                break;
                            case 'hashed':
                                echo "<input class='form-control' type='password' name='{$column}'>";
                                break;
                            case 'text':
                                echo "<textarea class='form-control editor' type='text' name='{$column}'>{$value}</textarea>";
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

<a onclick="javascript: return confirm('Are you sure?')" href="<?php $this->baseUrl() ?>admin/panel/model/<?php echo $model_slug ?>" class="btn btn-sm btn-warning btn-icon-split">
    <span class="icon text-white-50">
        <i class="fas fa-ban"></i>
    </span>
    <span class="text">Cancel</span>
</a>

<button type="submit" class="btn btn-sm btn-success btn-icon-split float-right">
    <span class="icon text-white-50">
        <i class="fas fa-save"></i>
    </span>
    <span class="text">Save</span>
</button>

</form>

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