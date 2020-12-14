document.addEventListener('DOMContentLoaded', function(e){
	
	poistaTarvittaessaTaytto("#vuosipaikat");
	
	alustaVuosipaikat("#vuosipaikat rect");
	alustaVuosipaikat("#vuosipaikat path");
	
	alustaLista("ul.vp_list span");
	
});


/*
 * liittyy: vuosilistaa vastaavan kauppiaan korostaminen kartalla
 */
function alustaLista(selector){
	
	var a =  document.querySelectorAll(selector);
	var i;

	a.forEach(elem => {
		
		// 
		elem.parentNode.addEventListener(
			"mouseover", 
			function(){
				korostaKartalta(`${elem.classList[0]}`, true);
			}
		);	

		elem.parentNode.addEventListener(
			"mouseout", 
			function(){
				korostaKartalta(`${elem.classList[0]}`, false);
			}
		);		

	});
}

/*
 * liittyy: kartalla valitun kohteen korostamine listalla 
 *
 * - poistetaan Inkscapen myyntiruuduissa käyttämät tyylimääritykset
 * - lisätään mouseover ja mouseout kutsut
 */
function alustaVuosipaikat(selector){
	
	var a =  document.querySelectorAll(selector);
	var i;
	
	a.forEach(elem => {
		
		elem.removeAttribute("style");
		
		
		// mouseover
		elem.addEventListener(
			"mouseover", 
			function(){
				korostaListalta(`${elem.id}`, true);
			}
		);
		
		// mouseout
		elem.addEventListener(
			"mouseout", 
			function(){
				korostaListalta(`${elem.id}`, false);
			}
		);
		
	});

}

/*
 * liittyy: kartalla valitun kohteen korostamine listalla 
 */
function korostaListalta(id, aktivoi) {
	
	var lstItems = document.getElementsByClassName(id);

	
	for (let item of lstItems) {
		
		if(aktivoi)
			item.parentNode.classList.add("active");
		else
			item.parentNode.classList.remove("active");
	}

}


/*
 * liittyy: vuosilistaa vastaavan kauppiaan korostaminen kartalla
 */
function korostaKartalta(foo, aktivoi){
	
	var svgElem = document.getElementById(foo);

	if(aktivoi){
		svgElem.classList.add('active');
		svgElem.setAttribute("opacity","1");
	}		
	else {
		svgElem.removeAttribute("class");
		svgElem.setAttribute("opacity","0.3495575");
	}
}


function poistaTarvittaessaTaytto(selector) {
	
	let elem = document.querySelector(selector);
	
	if(elem){
		//elem.removeAttribute("fill");
		elem.classList.add('vuosipaikka');
	}
	
}