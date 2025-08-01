// $(document).on('change','input[type="file"]',function(){
// 	// this.files[0].size recupera el tamaño del archivo
// 	// alert(this.files[0].size);
// 	console.log("funcion trabajando");
	
// 	var fileName = this.files[0].name;
// 	var fileSize = this.files[0].size;

// 	console.log(this);
// 	var ext = fileName.split('.').pop();

// 	//console.log(fileSize);

// 	if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
// 		if (fileSize < 3000000) {
// 			alert("cumple formato y peso");
// 		}else{
// 			alert("no cumple peso");
// 		}
		
// 	}else{
// 		alert("no cumple extension");
// 	}
// });



// function(inputFile){
// 	inputFile.on('change', function(){
		
// 		var fileName = this.files[0].name;
// 		var fileSize = this.files[0].size;
// 		var ext = fileName.split('.').pop();

// 		console.log(fileSize);

// 		if(ext == 'jpg' || ext == 'jpeg' || ext == 'png'){
// 			if (fileSize < 3000000) {
// 				alert("cumple formato y peso");
// 			}else{
// 				alert("no cumple peso");
// 			}
			
// 		}else{
// 			alert("no cumple extension");
// 		}
// 	});
// }