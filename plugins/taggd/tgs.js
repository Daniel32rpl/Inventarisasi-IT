$('#img_anatomi').click(function(e){
    var width = this.clientWidth;
    var height = this.clientHeight;

    var offset = $(this).offset();
    var x_input = (e.pageX - offset.left)/width;
    var y_input = (e.pageY - offset.top)/height;

    var image = document.getElementById('img_anatomi');
    var pgid3 = document.getElementById('pgid3').value;

    var options = {};
    var tags = [];

    var data = [
      Taggd.Tag.createFromObject({
        position: { x: x_input, y: y_input },
        text: '<div id="popup">'
                +'<label>Bagian Tubuh<\/label>'
                +'<input id="bagiantubuh" class="form-control" type="text"\/>'
                +'<input type="hidden" id="pgid3" value="'+ pgid3 +'" class="form-control"\/>'
                +'<label>Keterangan<\/label>'
                +'<textarea id="keterangan" class="form-control"><\/textarea><br\/>'
                +'<button id="button_add" type="button" formnovalidate class="btn btn-primary">Tambah<\/button> '
                +'<button id="button_cancel" type="button" formnovalidate class="btn btn-default">Batal<\/button>'
            +'<\/div>',
      }),
    ];

    var taggd = new Taggd(image, options, data, tags);

    $('#button_cancel').click(function(){
		taggd.deleteTag(0);
    });

    $('#button_add').click(function(){
        tag = taggd.getTag(0).toJSON();
        var x = tag['position']['x'];
        var y = tag['position']['y'];
        var bagiantubuh = $('#bagiantubuh').val();
        var keterangan = $('#keterangan').val();
        var pgid3 = $('#pgid3').val();
		
        $.ajax({
            type:"POST",
            url:"load_data.php?gid=anatomi&gid2=1",
            data:"posisix=" + x + "&posisiy=" + y + "&bagiantubuh=" + bagiantubuh + "&keterangan=" + keterangan + "&pgid3=" + pgid3,
            success: function(resdata){
				var res = resdata.split("|");
				var alr;
				
				if(res[0]==1){
					alert("Data Berhasil Disimpan");
				}else{
					alr = res[1];
					alert(alr);
				}
				
				location.reload(true);
			}
        });
    });
});

$(document).ready(function(){
    showTagEdit();
});