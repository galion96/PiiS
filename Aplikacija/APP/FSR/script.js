$(document).ready(function(){


  function load(url, type, dataType, callback) {
    $.ajax({url: url,
            type: type,
            dataType: dataType, 
            success: function(result){
              callback(result);
            },
            error: function(){
                console.log("err");
            }
     });
  }
  
  function parse(data){
    console.log(data);
    displayData(data.data);
  }

  function displayData(data){
    
    /*var tbody = document.createElement("tbody");
    
    for(var i = 0; i < data.length; i++){
        var tr = document.createElement("tr");
        var tdName = document.createElement("td");
        var tdNumber = document.createElement("td");
        var tdDate = document.createElement("td");
        var tdData = document.createElement("td");
        
        tdName.innerHTML = data[i].sensor_name;
        tdNumber.innerHTML = data[i].reading_count;
        tdDate.innerHTML = data[i].reading_time;
        tdData.innerHTML = data[i].reading_value;
        
        $(tr).append(tdName).append(tdNumber).append(tdDate).append(tdData)
        $(tbody).append(tr);
    }

    $(tbody).insertAfter("#tableHead");*/

    for(var i = 0; i < data.length; i++){
      console.log(data[i].serial_id);
      var boardItem = document.createElement("option");
      boardItem.setAttribute("value", data[i].id);
      boardItem.innerHTML = data[i].serial_id;
      $("#selectBoard").append(boardItem);
    }

      $( "#selectBoard" ).change(function() {
        $("#quickData").innerHTML = "";
        var id = $('#selectBoard :selected').attr("value");
        $.ajax({url: 'http://93.180.118.197:2565/piis/index.php?method=getAllBoardData&id_waspmote=' + id,
          type: "GET",
          dataType: "json", 
          success: function(result){
            console.log(result);

            for(var i in result.data){
              console.log(i);
            
              var div = document.createElement("div");
              div.setAttribute("class", "col-6 col-sm-3 placeholder");
              div.innerHTML = i;
              $("#quickData").append(div);
              /*console.log(data[i].serial_id);
              var boardItem = document.createElement("option");
              boardItem.setAttribute("value", data[i].id);
              boardItem.innerHTML = data[i].serial_id;
              $("#selectBoard").append(boardItem);*/
            }

          },
          error: function(err){
              console.log(err);
          }
        }); 
      });

      
  }

  load('http://93.180.118.197:2565/piis/index.php?method=getAllBoards', "GET", "json", parse);

});