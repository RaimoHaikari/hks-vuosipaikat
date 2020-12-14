document.addEventListener('DOMContentLoaded', function(e){
	
	poistaTarvittaessaTaytto("#vuosipaikat");
	
	alustaVuosipaikat("#vuosipaikat rect");
	alustaVuosipaikat("#vuosipaikat path");
	
	alustaLista(".hks_vp_collapse_btn-link");

});

const HEADER_EXT = '_collapse',
	LST_HOVER_BG = 'bg-danger',
	LST_ACT_BG = 'bg-dark',
	MAP_ACT_CLASS = 'hks_vuosipaikka_active',
	MAP_HOVERED_CLASS = 'hovered',
	MAP_SQUARE_OCP = 'hks_vuosipaikka';			// Varatun ruudun css-asetukset
	
let actSquare = null;

/*
 * Listan päivittäminen kartalle tehtyä valintaa vastaavaksi
 *
 * - klikattua ruutua vastaavan listakohdan avaaminen
 * - listaotsikon taustavärin asettaminen
 */
function aktivoiListalta(id) {
	
	jQuery(`#${id}`).collapse('toggle');

	const cList = document.getElementById(id).previousElementSibling.classList;

	// poistetan tarvittaessa mouseover/hover väritys
	if(cList.contains(LST_HOVER_BG)){
		cList.toggle(LST_HOVER_BG);
	}
	
	cList.toggle(LST_ACT_BG);

}

/*
 * Kartan alkuvalmistelut
 *
 * - poistetaan Inkscapen myyntiruuduissa käyttämät tyylimääritykset
 * - lisätään klikkauksen käsittely
 * - lisätään mouseover ja mouseout kutsut
 */
function alustaVuosipaikat(selector){
	
	var a =  document.querySelectorAll(selector);
	var i;
	
	const hoverExtension = '_heading';
	
	a.forEach(elem => {
		
		elem.removeAttribute("style");
				
		elem.addEventListener(
			"click", 
			function(){
				
				if(null === actSquare){				// avaus
					elem.classList.toggle(MAP_ACT_CLASS);
					actSquare = elem.id;	
				} else if (elem.id === actSquare) { // sulku
					elem.classList.toggle(MAP_ACT_CLASS);
					actSquare = null;	
				} else { 	// vaihto
	
					// listaa täytyy päivittää
					const activeHeaderId = `${actSquare}${hoverExtension}`;		
					document.getElementById(activeHeaderId).classList.toggle(LST_ACT_BG);
				
					// karttaruudut "vanha ja uusi"
					document.getElementById(actSquare).classList.toggle(MAP_ACT_CLASS);
					elem.classList.toggle(MAP_ACT_CLASS);
					
					actSquare = elem.id;	
				}

				
				aktivoiListalta(`${elem.id}${HEADER_EXT}`);
			}
		);
		

		// mouseover
		elem.addEventListener(
			"mouseover", 
			function(){
				korostaListalta(`${elem.id}${hoverExtension}`, "mouseover");
			}
		);	

		// mouseover
		elem.addEventListener(
			"mouseout", 
			function(){
				korostaListalta(`${elem.id}${hoverExtension}`, "mouseout");
			}
		);	

	});
}	

/*
 * Kartalla suoritettavan "hoverinnin" esittäminen myös listalla
 *
 * - korostetaan elementin väriä mikäli ei ole aktiivinen elementti
 * - korostetaan elementin väriä mikäli ei olla poistumassa klikkauksen jälkeen
 */
function korostaListalta(id, strEvent) {
	
	const cList = document.getElementById(id).classList;
	
	if(cList.contains(LST_ACT_BG) == false){
		
		// Ollaanko poistumassa klikkauksen jälkeen...
		if((cList.contains(LST_HOVER_BG) == false) && (strEvent == 'mouseout'))
			return;
		
		cList.toggle(LST_HOVER_BG);
	}

}
	
/*
 * Myyjät esittävän lista (accordion) alustaminen		
 */ 
function alustaLista(selector){
	
	const hoverExtension = '_heading';
	
	document.querySelectorAll(selector).forEach(button => {

		button.addEventListener('click', () => {
			
			const cardHeader = button.closest(".card-header"),
			squareIdArr = cardHeader.id.split("_"),
			squareId = `${squareIdArr[0]}_${squareIdArr[1]}`;
						
			if(null === actSquare){							// avaus
				cardHeader.classList.toggle(LST_ACT_BG);
				actSquare = squareId;	

				// korostetaan myös kartalla
				document.getElementById(squareId).classList.toggle(MAP_ACT_CLASS);
				
			} else if (squareId === actSquare) { 		// sulku
				cardHeader.classList.toggle(LST_ACT_BG);	
				
				// sammutetaan myös kartalta
				document.getElementById(squareId).classList.toggle(MAP_ACT_CLASS);
				
				actSquare = null;	
			} else { 	

				// sammutetaan vaihtuvan otsikon korostus
				const activeHeaderId = `${actSquare}${hoverExtension}`;			
				document.getElementById(activeHeaderId).classList.toggle(LST_ACT_BG);
				
				// sammutetaan myös kartalta
				document.getElementById(actSquare).classList.toggle(MAP_ACT_CLASS);				
				
				cardHeader.classList.toggle(LST_ACT_BG);
				actSquare = squareId;
				
				// korostetaan myös kartalla
				document.getElementById(actSquare).classList.toggle(MAP_ACT_CLASS);				
			}
			

		});
		
		
		// mouseover
		button.addEventListener(
			"mouseover", 
			function(){
				
				const cardHeader = button.closest(".card-header");
				
				cardHeader.classList.toggle(LST_HOVER_BG);
				
				korostaKartalta(cardHeader.id, true);
			}
		);	

		// mouseover
		button.addEventListener(
			"mouseout", 
			function(){
				
				const cardHeader = button.closest(".card-header");
				
				cardHeader.classList.toggle(LST_HOVER_BG);
				
				korostaKartalta(cardHeader.id, false);
			}
		);		
		
	});
	
}

/*
 * Listalla tapahtuvan hoveroinnen esitys myös kartalla
*/
function korostaKartalta(listId, aktivoi){
	
	const listIdArr = listId.split("_"),
		cardId = `${listIdArr[0]}_${listIdArr[1]}`;
		

	var svgElem = document.getElementById(cardId);


	if(aktivoi){
		svgElem.classList.add(MAP_HOVERED_CLASS);
	}		
	else {
		svgElem.classList.remove(MAP_HOVERED_CLASS);
	}
}




function poistaTarvittaessaTaytto(selector) {
	
	let elem = document.querySelector(selector);
	
	if(elem){
		elem.classList.add(MAP_SQUARE_OCP);
	}
	
}