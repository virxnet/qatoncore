<script src="<?php $this->baseUrl() ?>assets/admin/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
tinymce.init({
    selector: '.editor',
    toolbar_mode: 'sliding',
    toolbar: 'code codesample | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | fontselect fontsizeselect formatselect | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor | a11ycheck ltr rtl | showcomments addcomment restoredraft',
    browser_spellcheck: true,
    contextmenu: false,
    plugins: 'colorpicker textcolor print preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    autosave_ask_before_unload: true,
    autosave_interval: '10s',
    autosave_prefix: 'qaton-autosave-{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '60m',
    //skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_style: "body {margin: 10px}",
    codesample_languages: [
        {text:"PHP",value:"php"},
        {text:'HTML/XML',value:'markup'},
        {text:"HTML",value:"html"},
        {text:"Javascript",value:"javascript"},
        {text:"JSON",value:"json"},
        {text:"CSS",value:"css"},
        {text:"XML",value:"xml"},
        {text:"bash",value:"bash"},
        {text:"git",value:"git"},
        {text:"apacheconf",value:"apacheconf"},
        {text:"nginx",value:"nginx"},
        {text:"markdown",value:"markdown"},
        {text:"YAML",value:"yaml"},
        {text:"http",value:"http"},
        {text:"SQL",value:"sql"},
        {text:"mathml",value:"mathml"},
        {text:"SVG",value:"svg"},
        {text:"Clike",value:"clike"},
        {text:"ActionScript",value:"actionscript"},
        {text:"apl",value:"apl"},
        {text:"applescript",value:"applescript"},
        {text:"asciidoc",value:"asciidoc"},
        {text:"aspnet",value:"aspnet"},
        {text:"autoit",value:"autoit"},
        {text:"autohotkey",value:"autohotkey"},
        {text:"basic",value:"basic"},
        {text:"batch",value:"batch"},
        {text:"c",value:"c"},
        {text:"brainfuck",value:"brainfuck"},
        {text:"bro",value:"bro"},
        {text:"bison",value:"bison"},
        {text:"C#",value:"csharp"},
        {text:"C++",value:"cpp"},
        {text:"CoffeeScript",value:"coffeescript"},
        {text:"ruby",value:"ruby"},
        {text:"d",value:"d"},
        {text:"dart",value:"dart"},
        {text:"diff",value:"diff"},
        {text:"docker",value:"docker"},
        {text:"eiffel",value:"eiffel"},
        {text:"elixir",value:"elixir"},
        {text:"erlang",value:"erlang"},
        {text:"fsharp",value:"fsharp"},
        {text:"fortran",value:"fortran"},
        {text:"glsl",value:"glsl"},
        {text:"go",value:"go"},
        {text:"groovy",value:"groovy"},
        {text:"haml",value:"haml"},
        {text:"handlebars",value:"handlebars"},
        {text:"haskell",value:"haskell"},
        {text:"haxe",value:"haxe"},
        {text:"icon",value:"icon"},
        {text:"inform7",value:"inform7"},
        {text:"ini",value:"ini"},
        {text:"j",value:"j"},
        {text:"jade",value:"jade"},
        {text:"java",value:"java"},
        {text:"jsonp",value:"jsonp"},
        {text:"julia",value:"julia"},
        {text:"keyman",value:"keyman"},
        {text:"kotlin",value:"kotlin"},
        {text:"latex",value:"latex"},
        {text:"less",value:"less"},
        {text:"lolcode",value:"lolcode"},
        {text:"lua",value:"lua"},
        {text:"makefile",value:"makefile"},
        {text:"matlab",value:"matlab"},
        {text:"mel",value:"mel"},
        {text:"mizar",value:"mizar"},
        {text:"monkey",value:"monkey"},
        {text:"nasm",value:"nasm"},
        {text:"nim",value:"nim"},
        {text:"nix",value:"nix"},
        {text:"nsis",value:"nsis"},
        {text:"objectivec",value:"objectivec"},
        {text:"ocaml",value:"ocaml"},
        {text:"oz",value:"oz"},
        {text:"parigp",value:"parigp"},
        {text:"parser",value:"parser"},
        {text:"pascal",value:"pascal"},
        {text:"perl",value:"perl"},
        {text:"processing",value:"processing"},
        {text:"prolog",value:"prolog"},
        {text:"protobuf",value:"protobuf"},
        {text:"puppet",value:"puppet"},
        {text:"pure",value:"pure"},
        {text:"python",value:"python"},
        {text:"q",value:"q"},
        {text:"qore",value:"qore"},
        {text:"r",value:"r"},
        {text:"jsx",value:"jsx"},
        {text:"rest",value:"rest"},
        {text:"rip",value:"rip"},
        {text:"roboconf",value:"roboconf"},
        {text:"crystal",value:"crystal"},
        {text:"rust",value:"rust"},
        {text:"sas",value:"sas"},
        {text:"sass",value:"sass"},
        {text:"scss",value:"scss"},
        {text:"scala",value:"scala"},
        {text:"scheme",value:"scheme"},
        {text:"smalltalk",value:"smalltalk"},
        {text:"smarty",value:"smarty"},
        {text:"stylus",value:"stylus"},
        {text:"swift",value:"swift"},
        {text:"tcl",value:"tcl"},
        {text:"textile",value:"textile"},
        {text:"twig",value:"twig"},
        {text:"TypeScript",value:"typescript"},
        {text:"verilog",value:"verilog"},
        {text:"vhdl",value:"vhdl"},
        {text:"wiki",value:"wiki"}
    ],
    height: 600,
    branding: false,
    draggable_modal: true,
    content_css : "/assets/admin/styles/bootstrap.min.css"
});
tinymce.init({
    selector: '.editor_view_only',
    browser_spellcheck: true,
    contextmenu: false,
    plugins: 'autoresize wordcount codesample',
    //skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_style: "body {margin: 10px}",
    height: 600,
    branding: false,
    toolbar: '',
    menubar: '',
    content_css : "/assets/admin/styles/bootstrap.min.css",
    readonly: 1
});
</script>