<div class="row">
    @include('laraknife.form.text_2_4', ['name' => 'name', 'label' => 'Name', 'value' => $sysprop->name])
    @include('laraknife.form.text_2_4', ['name' => 'shortname', 'label' => 'Kurzname', 'value' => $sysprop->name])
</div>
<div class="row">
    @include('laraknife.form.text_2_4', ['name' => 'order', 'label' => 'Reihe', 'value' => $sysprop->order])
    @include('laraknife.form.text_2_4', ['name' => 'value', 'label' => 'Wert', 'value' => $sysprop->value])
</div>
<div class="row">
    @include('laraknife.form.bigtext_2_10', ['name' => 'info', 'label' => 'Info', 'value' => $sysprop->info, 'rows' => 2])
 </div>
 @include('laraknife.form.row_empty', ['width' => 2])
