<h4>Create</h4>
<form method="post" action="<?php $this->baseUrl() ?>admin/panel/table/insert/<?php echo $model_slug ?>/0">
<table class="table table-striped table-bordered table-hover">
    <?php foreach ($schema as $column => $properties) : ?>
        <tr>
            <th class="text-right" style="width: 10%;">
                <?php echo $column ?>
            </th>
            <td>
                <?php
                switch ($properties['type']) {
                    case 'string':
                    case 'int':
                    case 'integer':
                    case 'float':
                    case 'double':
                        echo "<input class='form-control' type='text' name='{$column}'>";
                        break;
                    case 'foreign':
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
                        }
                        break;
                    case 'text':
                        echo "<textarea class='form-control editor' type='text' name='{$column}'></textarea>";
                        break;
                }
                echo "<small>";
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
                    echo "&nbsp;";
                }
                echo "</small>";
                ?>
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



