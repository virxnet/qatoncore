
<h4>Data</h4>
<form method="post" enctype="multipart/form-data" action="<?php $this->baseUrl() ?>admin/panel/table/update/<?php echo $model_slug ?>/<?php echo $data['id'] ?>">
<table class="table table-striped table-bordered table-hover ">
    <?php foreach ($data as $column => $value) : ?>
        <tr>
            <th class="text-right" style="width: 10%;">
                <?php echo $column ?>
                <br>
                <small><?php echo @$schema[$column]['type'] ?></small>
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
                            case 'html':
                                echo '
                                <style type="text/css" media="screen">
                                    #ace_' . $column . ' { 
                                        height: 500px;
                                        width: 50%;
                                        float: left;
                                    }
                                    #ace_preview_' . $column . ' { 
                                        height: 500px;
                                        width: 50%;
                                        float: right;
                                        background: #fff;
                                        padding: 10px;
                                        border: 1px solid #000;
                                    }
                                </style>
                                ';
                                echo "<div id='ace_{$column}'/>{$value}</div>";
                                echo "<div id='ace_preview_{$column}' contenteditable='false'/>{$value}</div>";
                                echo "<textarea type='text' name='{$column}' id='html_{$column}'>{$value}</textarea>";
                                echo '
                                <script>
                                    var ace_preview = $("#ace_preview_' . $column . '");
                                    var textarea = $("#html_' . $column . '").hide();
                                    var ace_editor = ace.edit("ace_' . $column . '");
                                    ace_editor.setFontSize("16px");
                                    ace_editor.setTheme("ace/theme/monokai");
                                    ace_editor.session.setMode("ace/mode/html");
                                    ace_editor.getSession().setValue(textarea.val());
                                    ace_editor.getSession().on(\'change\', function(){
                                        textarea.val(ace_editor.getSession().getValue());
                                        ace_preview.html(ace_editor.getSession().getValue());
                                    });
                                    textarea.val(ace_editor.getSession().getValue());
                                    ace_preview.html(ace_editor.getSession().getValue());
                                </script>
                                ';
                                break;
                            case 'text':
                                echo "<textarea class='form-control editor' type='text' name='{$column}'>{$value}</textarea>";
                                break;
                            case 'file':
                                if (isset($value['url'])) {
                                    if (isset($value['meta']['type'])
                                        && substr($value['meta']['type'], 0, 5) == 'image'
                                    ) {
                                        echo "<img width='300' src='{$value['url']}'> <br/>";
                                    }
                                    echo "[<a href='?{$value['query']}'>FILE</a>] &nbsp; ";
                                } else {
                                    echo "[FILE] &nbsp; ";
                                }
                                echo "<input name='{$column}' type='file' />";
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