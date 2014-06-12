@extends('layout.front')

@section('content')
    {{ HTML::style('js/fancytree/skin-bootstrap/ui.fancytree.css') }}
    {{ HTML::script('js/fancytree/jquery.fancytree-all.js') }}

    {{ HTML::script('js/fancytree/src/jquery.fancytree.dnd.js') }}
    {{ HTML::script('js/fancytree/src/jquery.fancytree.edit.js') }}
    {{ HTML::script('js/fancytree/src/jquery.fancytree.gridnav.js') }}
    {{ HTML::script('js/fancytree/src/jquery.fancytree.table.js') }}
    {{ HTML::script('js/fancytree/src/jquery.fancytree.glyph.js') }}

<script type="text/javascript">
    var CLIPBOARD = null;

$(document).ready(function(){
    $("#mainTree").fancytree({
        checkbox: true,
        titlesTabbable: true,     // Add all node titles to TAB chain
        source: {
            url: '{{ URL::to('menu/menudata') }}'
        },
        // extensions: ["edit", "table", "gridnav"],
        extensions: ["edit", "dnd", "table", "gridnav","glyph"],

        dnd: {
          preventVoidMoves: true,
          preventRecursiveMoves: true,
          autoExpandMS: 400,
          dragStart: function(node, data) {
            return true;
          },
          dragEnter: function(node, data) {
            // return ["before", "after"];
            return true;
          },
          dragDrop: function(node, data) {
            data.otherNode.moveTo(node, data.hitMode);
          }
        },
        edit: {
        },
        table: {
          indentation: 20,
          nodeColumnIdx: 2,
          checkboxColumnIdx: 0
        },
        gridnav: {
          autofocusInput: false,
          handleCursorKeys: true
        },


        lazyLoad: function(event, data) {
              data.result = {url: "../demo/ajax-sub2.json"};
        },
        renderColumns: function(event, data) {
            var node = data.node,
            $select = $("<select />"),
            $tdList = $(node.tr).find(">td");

            // (index #0 is rendered by fancytree by adding the checkbox)
            if( node.isFolder() ) {
            // make the title cell span the remaining columns, if it is a folder:
            $tdList.eq(2)
                .prop("colspan", 6)
                .nextAll().remove();
            }

            $tdList.eq(1).text(node.getIndexHier()).addClass("alignRight");
            // (index #2 is rendered by fancytree)
            // $tdList.eq(3).text(node.key);
            $tdList.eq(3).html("<input class='mparam' type='input' value='" + node.key + "'>");
            $tdList.eq(4).html("<input class='mparam' type='input' value='" + node.key + "'>");
            $tdList.eq(5).html("<input type='checkbox' value='" + node.key + "'>");
            $tdList.eq(6).html("<input type='checkbox' value='" + node.key + "'>");
            $("<option />", {text: "a", value: "a"}).appendTo($select);
            $("<option />", {text: "b"}).appendTo($select);
            $tdList.eq(7).html($select);
        }
    }).on("nodeCommand", function(event, data){
        // Custom event handler that is triggered by keydown-handler and
        // context menu:
        var refNode, moveMode,
          tree = $(this).fancytree("getTree"),
          node = tree.getActiveNode();

        switch( data.cmd ) {
        case "moveUp":
          node.moveTo(node.getPrevSibling(), "before");
          node.setActive();
          break;
        case "moveDown":
          node.moveTo(node.getNextSibling(), "after");
          node.setActive();
          break;
        case "indent":
          refNode = node.getPrevSibling();
          node.moveTo(refNode, "child");
          refNode.setExpanded();
          node.setActive();
          break;
        case "outdent":
          node.moveTo(node.getParent(), "after");
          node.setActive();
          break;
        case "rename":
          node.editStart();
          break;
        case "remove":
          node.remove();
          break;
        case "addChild":
          refNode = node.addChildren({
            title: "New node",
            isNew: true
          });
          node.setExpanded();
          refNode.editStart();
          break;
        case "addSibling":
          refNode = node.getParent().addChildren({
            title: "New node",
            isNew: true
          }, node.getNextSibling());
          refNode.editStart();
          break;
        case "cut":
          CLIPBOARD = {mode: data.cmd, data: node};
          break;
        case "copy":
          CLIPBOARD = {
            mode: data.cmd,
            data: node.toDict(function(n){
              delete n.key;
            })
          };
          break;
        case "clear":
          CLIPBOARD = null;
          break;
        case "paste":
            if( CLIPBOARD.mode === "cut" ) {
                // refNode = node.getPrevSibling();
                CLIPBOARD.data.moveTo(node, "child");
                CLIPBOARD.data.setActive();
            } else if( CLIPBOARD.mode === "copy" ) {
                node.addChildren(CLIPBOARD.data).setActive();
            }
            break;
        default:
            alert("Unhandled command: " + data.cmd);
            return;
        }

    }).on("keydown", function(e){
    var c = String.fromCharCode(e.which),
      cmd = null;

    if( c === "N" && e.ctrlKey && e.shiftKey) {
      cmd = "addChild";
    } else if( c === "C" && e.ctrlKey ) {
      cmd = "copy";
    } else if( c === "V" && e.ctrlKey ) {
      cmd = "paste";
    } else if( c === "X" && e.ctrlKey ) {
      cmd = "cut";
    } else if( c === "N" && e.ctrlKey ) {
      cmd = "addSibling";
    } else if( e.which === $.ui.keyCode.DELETE ) {
      cmd = "remove";
    } else if( e.which === $.ui.keyCode.F2 ) {
      cmd = "rename";
    } else if( e.which === $.ui.keyCode.UP && e.ctrlKey ) {
      cmd = "moveUp";
    } else if( e.which === $.ui.keyCode.DOWN && e.ctrlKey ) {
      cmd = "moveDown";
    } else if( e.which === $.ui.keyCode.RIGHT && e.ctrlKey ) {
      cmd = "indent";
    } else if( e.which === $.ui.keyCode.LEFT && e.ctrlKey ) {
      cmd = "outdent";
    }
    if( cmd ){
      $(this).trigger("nodeCommand", {cmd: cmd});
      return false;
    }
  });

  /*
   * Context menu (https://github.com/mar10/jquery-ui-contextmenu)
   */
  $("#tree").contextmenu({
    delegate: "span.fancytree-title",
    menu: [
      {title: "Edit", cmd: "rename", uiIcon: "ui-icon-pencil" },
      {title: "Delete", cmd: "remove", uiIcon: "ui-icon-trash" },
      {title: "----"},
      {title: "New node", cmd: "addChild", uiIcon: "ui-icon-plus" },
      {title: "New child node", cmd: "addChild", uiIcon: "ui-icon-arrowreturn-1-e" },
      {title: "----"},
      {title: "Cut", cmd: "cut", uiIcon: "ui-icon-scissors"},
      {title: "Copy", cmd: "copy", uiIcon: "ui-icon-copy"},
      {title: "Paste", cmd: "paste", uiIcon: "ui-icon-clipboard", disabled: true }
      ],
    beforeOpen: function(event, ui) {
      var node = $.ui.fancytree.getNode(ui.target);
      $("#tree").contextmenu("enableEntry", "paste", !!CLIPBOARD);
      node.setActive();
    },
    select: function(event, ui) {
      var that = this;
      // delay the event, so the menu can close and the click event does
      // not interfere with the edit control
      setTimeout(function(){
        $(that).trigger("nodeCommand", {cmd: ui.cmd});
      }, 100);
    }
  });
});
</script>

<style type="text/css">
input.mparam{
    width:25px;
}

#mainTree select{
    width:100px;
}

.icon-* {
    width: 1.2em;
    height: 1.2em;
}
</style>

<div class="row-fluid">
    <div class="span8">
        <table id="mainTree" class="table">
            <colgroup>
            <col width="30px"></col>
            <col width="50px"></col>
            <col width="250px"></col>
            <col width="50px"></col>
            <col width="50px"></col>
            <col width="30px"></col>
            <col width="30px"></col>
            <col width="50px"></col>
            </colgroup>
            <thead>
              <tr> <th></th> <th>#</th> <th></th> <th>Ed1</th> <th>Ed2</th> <th>Rb1</th> <th>Rb2</th> <th>Cb</th></tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
    <div class="span4">

    </div>
</div>
@stop