<?php
/*Par Thierry POINOT
 * poinot.thierry@gmail.com
 * http://poinot.thierry.free.fr
 * le lundi 28 mai 2007 de 10h43 à 11h00
 * A faire : une inscription à un genre de newsletter
 * qui prévient les utilisateurs le jour d'une fête
 * du prénom qu'il veulent !uniquement à condition que le script soit visité tous les jours !*/

global $fetes_prenoms;

$fetes_prenoms[1][1] = "Maria";
$fetes_prenoms[2][1] = "Basile, Vassili";
$fetes_prenoms[3][1] = "Geneviève, Ginette";
$fetes_prenoms[4][1] = "Eddie, Eddy, Odilon";
$fetes_prenoms[5][1] = "Amélien, Édouard, Édouardine, Eduardo, Edward, Émilienne, Ted, Teddy";
$fetes_prenoms[6][1] = "Melaine, Tiphaine";
$fetes_prenoms[7][1] = "Cédric, Lucienne, Ray, Raymond, Raymonde, Virginie";
$fetes_prenoms[8][1] = "Lucien, Peggy";
$fetes_prenoms[9][1] = "Alexia, Alicia, Alison, Alisone, Alisson, Alissone, Alix, Allison, Allisson, Allyson, Alyson, Alysson";
$fetes_prenoms[10][1] = "Bill, Billy, Guillaume, Guillaumette, Guillemette, Guillermo, William, Willy";
$fetes_prenoms[11][1] = "Paulin";
$fetes_prenoms[12][1] = "Arcadie, Césarine, Tania, Tanya, Tatiana, Tatienne, Tigrane";
$fetes_prenoms[13][1] = "Hilaire, Hilary, Yvette";
$fetes_prenoms[14][1] = "Nina";
$fetes_prenoms[15][1] = "Amalric, Amaury, Maur, Rachel, Rachelle, Rémi, Rémy, Tarsice";
$fetes_prenoms[16][1] = "Honorat, Marceau, Marcel, Marcello, Marcelo, Priscilla, Priscille";
$fetes_prenoms[17][1] = "Anthony, Antoni, Antonio, Antony, Roseline, Roselyne";
$fetes_prenoms[18][1] = "Gwendal, Prisca";
$fetes_prenoms[19][1] = "Mario, Marius";
$fetes_prenoms[20][1] = "Bastien, Bastienne, Fabien, Fabienne, Sébastian, Sébastien, Sébastienne";
$fetes_prenoms[21][1] = "Agnès";
$fetes_prenoms[22][1] = "Anastase, Vince, Vincent";
$fetes_prenoms[23][1] = "Barnard";
$fetes_prenoms[24][1] = "Fr. de Sales, Paco, Paquito, Soizic, Tim";
$fetes_prenoms[25][1] = "Apollos, Conv. St Paul";
$fetes_prenoms[26][1] = "Albéric, Paola, Paula, Paule, Paulette, Pauline, Timothée";
$fetes_prenoms[27][1] = "Angèle, Angélie, Angélina, Angéline, Angélique, Angelina";
$fetes_prenoms[28][1] = "Th. d'Aquin";
$fetes_prenoms[29][1] = "Gildas";
$fetes_prenoms[30][1] = "Aldegonde, Bathilde, Bathylle, Bob, Jacinte, Jacinthe, Martina, Martine";
$fetes_prenoms[31][1] = "Marcelle, Nikita";
$fetes_prenoms[1][2] = "Ella, Elle, Ellenita, Ellie, Viridiana";
$fetes_prenoms[2][2] = "Présentation, Théophane";
$fetes_prenoms[3][2] = "Blaise, Iadine, Oscar";
$fetes_prenoms[4][2] = "Bérénice, Gilberto, Vanessa, Vanina, Vannessa, Véronica, Véronique";
$fetes_prenoms[5][2] = "Agatha, Agathe, Avit";
$fetes_prenoms[6][2] = "Amand, Amanda, Amande, Doris, Dorothée, Gaston";
$fetes_prenoms[7][2] = "Dorian, Doriane, Eugénie, Théodora";
$fetes_prenoms[8][2] = "Jackie, Jacky, Jacqueline, Jacquette, Jacquotte";
$fetes_prenoms[9][2] = "Apolline";
$fetes_prenoms[10][2] = "Arnaud, Arnaude, Arnauld";
$fetes_prenoms[11][2] = "ND Lourdes";
$fetes_prenoms[12][2] = "Eulalie, Félicienne, Félix";
$fetes_prenoms[13][2] = "Béa, Béatrice, Jordan, Jordane, Jordanne, Lauriane";
$fetes_prenoms[14][2] = "Tino, Valentin";
$fetes_prenoms[15][2] = "Claude, Faustin";
$fetes_prenoms[16][2] = "Éliaz, Julienne, Lucille, Onésime, Paméla, Pamphile";
$fetes_prenoms[17][2] = "Alexane, Alexi, Alexian, Alexiane, Alexie, Alexis, Alexy";
$fetes_prenoms[18][2] = "Bernadette, Flavien, Nadette, Nadine, Siméon";
$fetes_prenoms[19][2] = "Gabin";
$fetes_prenoms[20][2] = "Aimée";
$fetes_prenoms[21][2] = "Dinan, P. Damien";
$fetes_prenoms[22][2] = "Isa, Isabel, Isabella, Isabelle, Terry";
$fetes_prenoms[23][2] = "Lazare, Polycarpe";
$fetes_prenoms[24][2] = "Modeste";
$fetes_prenoms[25][2] = "Roméo";
$fetes_prenoms[26][2] = "Nestor";
$fetes_prenoms[27][2] = "Honorine";
$fetes_prenoms[28][2] = "Antoinette, Romain, Roman, Romane";
$fetes_prenoms[29][2] = "Auguste";
$fetes_prenoms[1][3] = "Albin, Albina, Albine, Aubin, Aubine, Dave";
$fetes_prenoms[2][3] = "Charles le Bon, Jaouen, Joévin";
$fetes_prenoms[3][3] = "Guennolé, Guénolé, Gwénola, Gwenolé";
$fetes_prenoms[4][3] = "Casimir, Casper, Humbert";
$fetes_prenoms[5][3] = "Olive, Olivette, Olivia, Virgil, Virgile";
$fetes_prenoms[6][3] = "Colette, Nicole, Nicoletta";
$fetes_prenoms[7][3] = "Félicité, Perpétue";
$fetes_prenoms[8][3] = "Jean de D.";
$fetes_prenoms[9][3] = "Fanchon, Francesca, Françoise";
$fetes_prenoms[10][3] = "Anastasia, Anastasie, Anasthasia, Anasthasie, Vivien, Vivienne";
$fetes_prenoms[11][3] = "Rosine";
$fetes_prenoms[12][3] = "Elphège, Justine, Maximilien, Maximilienne, Maximillien, Pol";
$fetes_prenoms[13][3] = "Rodrigue";
$fetes_prenoms[14][3] = "Mathilde, Maud, Maude";
$fetes_prenoms[15][3] = "Louisa, Louise, Louisette, Lucrèce";
$fetes_prenoms[16][3] = "Bénédict, Bénédicte";
$fetes_prenoms[17][3] = "Pat, Patrice, Patricia, Patricio, Patrick, Patty";
$fetes_prenoms[18][3] = "Cyril, Cyrille, Salvatore";
$fetes_prenoms[19][3] = "José, Josée, Joséfina, Josep, Joseph, Josèphe, Joséphine, Josette, Josiane, Josianne, Jozef";
$fetes_prenoms[20][3] = "Alessandra, Herbert, Svetlana, Wulfran";
$fetes_prenoms[21][3] = "Axel, Clémence";
$fetes_prenoms[22][3] = "Léa, Leah, Leïla, Léïla, Lélia, Lia, Lila";
$fetes_prenoms[23][3] = "Rebecca, Rébecca, Victorien";
$fetes_prenoms[24][3] = "Aldemar, Cath de Su., Kathy, Katia, Katie, Katy, Ketty, Kristell";
$fetes_prenoms[25][3] = "Annonciade, Annonciation";
$fetes_prenoms[26][3] = "Lara, Larissa";
$fetes_prenoms[27][3] = "Habib";
$fetes_prenoms[28][3] = "Gontran";
$fetes_prenoms[29][3] = "Gladys, Gwladys, Moïra";
$fetes_prenoms[30][3] = "Amédé, Amédée";
$fetes_prenoms[31][3] = "Amos, Babine, Ben, Benjamin, Benjamine, Benny";
$fetes_prenoms[1][4] = "Hugues, Valéry";
$fetes_prenoms[2][4] = "Sandie, Sandra, Sandrine, Sandy, Alexandrine, Cendrine";
$fetes_prenoms[3][4] = "Dick, Irina, Marie-France, Ricardo, Riccardo, Richard";
$fetes_prenoms[4][4] = "Alèthe, Alette, Isidor, Isidore, Maya, Odin";
$fetes_prenoms[5][4] = "Irène";
$fetes_prenoms[6][4] = "Marcellin";
$fetes_prenoms[7][4] = "J.B. de la S.";
$fetes_prenoms[8][4] = "Constance, Julie";
$fetes_prenoms[9][4] = "Gauthier, Gautier, Walter";
$fetes_prenoms[10][4] = "Fulbert, Fulberte";
$fetes_prenoms[11][4] = "Stan, Stanislas";
$fetes_prenoms[12][4] = "Jules, Julio, Zéno";
$fetes_prenoms[13][4] = "Ida";
$fetes_prenoms[14][4] = "Lidwine, Maxime";
$fetes_prenoms[15][4] = "Paterne";
$fetes_prenoms[16][4] = "Benoît-Joseph";
$fetes_prenoms[17][4] = "Anicet";
$fetes_prenoms[18][4] = "Greta, Parfait";
$fetes_prenoms[19][4] = "Emma, Werner";
$fetes_prenoms[20][4] = "Giraud, Hildegonde, Odette";
$fetes_prenoms[21][4] = "Anselme, Selma";
$fetes_prenoms[22][4] = "Alexandra, Alexandre";
$fetes_prenoms[23][4] = "George, Georges, Georgette, Georgia, Georgiane, Georgina, Georgine, Jordi, Youri";
$fetes_prenoms[24][4] = "Fidel, Fidèle, Fortunat";
$fetes_prenoms[25][4] = "Marc, Mark, Markus";
$fetes_prenoms[26][4] = "Alda, Alida, Clet";
$fetes_prenoms[27][4] = "Zita";
$fetes_prenoms[28][4] = "Louis-Marie, Valérie";
$fetes_prenoms[29][4] = "Ava, Cathy, Hughes, Hugo, Huguette, Kate, Katerine, Katherine, Kathleen, Kathryn, Katrina, Katrine";
$fetes_prenoms[30][4] = "Archibald, Marien, Robert, Roberte, Roparz, Rosemonde";
$fetes_prenoms[1][5] = "Brieuc, Jérémi, Jérémie, Jeremy, Muguet, Muguette, Siegmund, Sigismond, Tamara";
$fetes_prenoms[2][5] = "Antonin, Athanase, Borice, Boris, Flamine, Gaubert, Zoé";
$fetes_prenoms[3][5] = "Alessandro, Alex, Alexander, Filip, Filipe, Filippo, Juvénal, Phil. Jacq., Philip, Philipp, Philippa, Philippe, Phillip, Phillipe";
$fetes_prenoms[4][5] = "Florian, Sylvain, Sylvaine";
$fetes_prenoms[5][5] = "Ange, Judith";
$fetes_prenoms[6][5] = "Caline, Prudence";
$fetes_prenoms[7][5] = "Domitille, Flavie, Gisèle";
$fetes_prenoms[8][5] = "Désiré, Désirée, Dunvel, Jeannine, Jenny, Juanita";
$fetes_prenoms[9][5] = "Isaïe, Pacôme, Tudi";
$fetes_prenoms[10][5] = "Simona, Simone, Solange";
$fetes_prenoms[11][5] = "Estelle, Étoile, Mayeul, Stella, Waldemar";
$fetes_prenoms[12][5] = "Achille";
$fetes_prenoms[13][5] = "Imelda, Maël, Maëla, Maële, Maëlle, Rolande";
$fetes_prenoms[14][5] = "Mathias, Matthias";
$fetes_prenoms[15][5] = "Denise, Primaël, Victorin";
$fetes_prenoms[16][5] = "Honoré, Weena";
$fetes_prenoms[17][5] = "Pascal, Pascale, Pascaline, Pascalle";
$fetes_prenoms[18][5] = "Cora, Coralie, Corinna, Corinne, Éric, Érica, Erich, Erick, Erik, Erika";
$fetes_prenoms[19][5] = "Célestin, Célestine, Erwan, Erwin, Yves, Yvon, Yvonne";
$fetes_prenoms[20][5] = "Bernardin, Roxane, Roxanne";
$fetes_prenoms[21][5] = "Constantin";
$fetes_prenoms[22][5] = "Émile, Emilio, Julia, Miloud, Quitterie, Rita";
$fetes_prenoms[23][5] = "Didier";
$fetes_prenoms[24][5] = "Amaël, Donatien, Rogatien";
$fetes_prenoms[25][5] = "Sofia, Sophia, Sophie";
$fetes_prenoms[26][5] = "Béranger, Bérenger, Bérengère";
$fetes_prenoms[27][5] = "Hildevert";
$fetes_prenoms[28][5] = "Germain";
$fetes_prenoms[29][5] = "Adhémar, Aymar, Aymard, Maximin";
$fetes_prenoms[30][5] = "Ferdinand, Janin, Janine, Janne, Jeanine, Jeanne, Johanna, Johanne, Loraine";
$fetes_prenoms[31][5] = "Pernelle, Péroline, Perrette, Perrine, Pétronille, Visitation";
$fetes_prenoms[1][6] = "Justin, Ronan";
$fetes_prenoms[2][6] = "Blandine";
$fetes_prenoms[3][6] = "Kévin";
$fetes_prenoms[4][6] = "Clothilde, Clotilde";
$fetes_prenoms[5][6] = "Boniface, Igor";
$fetes_prenoms[6][6] = "Claudette, Claudia, Claudie, Claudine, Claudio, Claudius, Norbert";
$fetes_prenoms[7][6] = "Gilbert, Maïté, Marie-Thérèse, Mériadec";
$fetes_prenoms[8][6] = "Armand, Armande, Armando, Mars, Médard, Médéric, Prune";
$fetes_prenoms[9][6] = "Diana, Diane, Éphrem, Félicien";
$fetes_prenoms[10][6] = "Gloria, Landry";
$fetes_prenoms[11][6] = "Barnabé, Yolande";
$fetes_prenoms[12][6] = "Guy";
$fetes_prenoms[13][6] = "Antoine de P., Antoine, Andoni, Anthony, Anton, Antonella ,Antonio, Antony, Titouan, Toine, Tony";
$fetes_prenoms[14][6] = "Candice, Élisée, Ruffin, Rufin, Valère";
$fetes_prenoms[15][6] = "Germaine";
$fetes_prenoms[16][6] = "Aleyde, Argan, Aurélien, Ferréol, J.F. Régis, Lutgarde, Régis";
$fetes_prenoms[17][6] = "Hervé, Rainer, Rainier";
$fetes_prenoms[18][6] = "Cassandra, Cassandre, Léonce";
$fetes_prenoms[19][6] = "Gervais, Gervaise, Micheline, Romuald";
$fetes_prenoms[20][6] = "Silvère";
$fetes_prenoms[21][6] = "Aloïs, Aloïse, Gina, Gino, Gonzague, Loïs, Ralf, Ralph, Rodolfo, Rodolph, Rodolphe, Rudi, Rudolf, Rudy";
$fetes_prenoms[22][6] = "Alban, Albane, Albanie, Albanne, Albannie, Albe, Aubaine, Auban";
$fetes_prenoms[23][6] = "Audrey";
$fetes_prenoms[24][6] = "Baptiste, Hans, Ivan, Jean, Jean-Baptiste, Joan, Joannie, Joanny, Yann, Yannick, Yoann, Yvan";
$fetes_prenoms[25][6] = "Aliénor, Éléonore, Lore, Nora, Prosper, Salomon";
$fetes_prenoms[26][6] = "Anthelme, Olympe";
$fetes_prenoms[27][6] = "Fernand, Fernande";
$fetes_prenoms[28][6] = "Irénée";
$fetes_prenoms[29][6] = "Esmeralda, Esméralda, Judy, Pablo, Paul, Peter, Peters, Petr, Petra, Pierre, Pierrette, Pierrick, Pierrot, Pietro";
$fetes_prenoms[30][6] = "Adolphe, Martial";
$fetes_prenoms[1][7] = "Aaron, Dietrich, Dirck, Dirk, Élyse, Esther, Goulven, Servan, Servane, Thierry, Thiery";
$fetes_prenoms[2][7] = "Martinien";
$fetes_prenoms[3][7] = "Anatole, Thomas, Tom";
$fetes_prenoms[4][7] = "Berthe, Florent, Lilian, Liliane, Lillianne";
$fetes_prenoms[5][7] = "Antoine, Toni, Tonio, Tony";
$fetes_prenoms[6][7] = "Godeliève, Macrine, Marietta, Mariette, Nolwenn";
$fetes_prenoms[7][7] = "Aubierge, Raoul";
$fetes_prenoms[8][7] = "Edgar, Edgard, Thibaud, Thibault, Thibaut, Thiébaud, Thor";
$fetes_prenoms[9][7] = "Amandine, Blanca, Hermine, Irma, Mariana, Mariane, Marianne";
$fetes_prenoms[10][7] = "Ulric, Ulrich";
$fetes_prenoms[11][7] = "Benoist, Benoît, Benoîte, Olga";
$fetes_prenoms[12][7] = "Oliver, Olivier";
$fetes_prenoms[13][7] = "Clélia, Enrique, Eugène, Harry, Heinrich, Henri, Henriette, Henrik, Henry, Joël, Joëlle, Mildred";
$fetes_prenoms[14][7] = "Camille";
$fetes_prenoms[15][7] = "Bonaventure, Don, Donald, Vlad, Vladimir, Wladimir";
$fetes_prenoms[16][7] = "Carmen, Elvire, ND Mt Carmel";
$fetes_prenoms[17][7] = "Arlette, Carole, Caroline, Charline, Charlotte, Marcelline, Victoria";
$fetes_prenoms[18][7] = "Arnould, Émilien, Freddy, Frédéric, Frédérick, Frédérik, Frédérike, Frédérique, Frida";
$fetes_prenoms[19][7] = "Arsène";
$fetes_prenoms[20][7] = "Éliane, Élie, Éliette, Hélyette, Marina, Marine, Marinette, Marjorie";
$fetes_prenoms[21][7] = "Domnin, Victor";
$fetes_prenoms[22][7] = "Maddy, Madeleine, Magali, Magalie, Magaly, Maggy, Marie-Madeleine";
$fetes_prenoms[23][7] = "Appolinaire, Appoline, Appolos, Brigitte";
$fetes_prenoms[24][7] = "Christel, Christelle, Christina, Christine, John, Johnny, Ségolène";
$fetes_prenoms[25][7] = "Christopher, Jack, Jacques, Jacquine, Jacquot, Jake, Jakob, James, Jim, Jimmy, Kristopher, Valentine";
$fetes_prenoms[26][7] = "Ana, Anaël, Anaële, Anaelle, Anaëlle, Anaïs, Anick, Anita, Anna, Annabel, Annabella, Annabelle, Annaëlle, Anne, Annick, Annie, Annik, Annique, Anny, Anouchka, Anouck, Anouk, Joachim, Joris, Nancy";
$fetes_prenoms[27][7] = "Nathalie, Viktor";
$fetes_prenoms[28][7] = "Cristina, Kristina, Samson";
$fetes_prenoms[29][7] = "Béatrix, Gaud, Loup, Marthe, Olaf";
$fetes_prenoms[30][7] = "Juliette";
$fetes_prenoms[31][7] = "Ignace de L., Ignace, Ignacio";
$fetes_prenoms[1][8] = "Alfonso, Alphonse, Alphonsine, Arcadius, Arcady, Éléazar, Espérance";
$fetes_prenoms[2][8] = "Eusèbe, Julien, Julien-Ey.";
$fetes_prenoms[3][8] = "Lydia, Lydiane, Lydie";
$fetes_prenoms[4][8] = "J.M. Vianney, Jean-Marie, Vianney";
$fetes_prenoms[5][8] = "Abel, Abélard, Abélia, Abella, Oswald";
$fetes_prenoms[6][8] = "Marlène, Octavien, Transfiguration";
$fetes_prenoms[7][8] = "Gaétan, Gaëtan, Gaétane, Gaëtane, Gaétanne";
$fetes_prenoms[8][8] = "Cyriaque, Domenico, Dominic, Dominick, Dominik, Dominique";
$fetes_prenoms[9][8] = "Amour";
$fetes_prenoms[10][8] = "Dieudonné, Larry, Laura, Lauranne, Laure, Laurence, Laurent, Laurentine, Laurette, Laurie";
$fetes_prenoms[11][8] = "Claire, Clara, Géry, Gilberte, Susan, Susanne, Suzane, Suzanna, Suzanne, Suzel, Suzette, Suzon, Suzy";
$fetes_prenoms[12][8] = "Clarisse";
$fetes_prenoms[13][8] = "Cassien, Hippolyte, Hyppolite, Maxim, Radegonde, Samantha";
$fetes_prenoms[14][8] = "Arnold, Évrard";
$fetes_prenoms[15][8] = "Alfred, Anne-Marie, Manon, Mariam, Marie, Marie-Laure, Marielle, Marilyn, Marion, Marjolaine, Marylin, Maryline, Marylise, Maryse, Maryvonne, Mercédès, Mireille, Miriam, Muriel, Murielle, Myriam";
$fetes_prenoms[16][8] = "Armel, Armele, Armelle, Roch";
$fetes_prenoms[17][8] = "Hyacinthe";
$fetes_prenoms[18][8] = "Élen, Éline, Helen, Héléna, Hélène, Laetitia, Laétitia, Léna, Lénaïc, Milène, Nelly";
$fetes_prenoms[19][8] = "Eudes, Jean Eudes, Mylène";
$fetes_prenoms[20][8] = "Bernard, Bernhard, Philibert, Philiberte, Sam, Sammy, Samuel, Samy";
$fetes_prenoms[21][8] = "Ahmed, Christophe, Grâce, Gracieuse, Noémie, Ombeline, Privat";
$fetes_prenoms[22][8] = "Fabrice, Fabrizio, Siegfried, Symphorien";
$fetes_prenoms[23][8] = "Églantine, Rosa, Rose de L., Rose, Rosette, Rosita, Rosy, Rozenn";
$fetes_prenoms[24][8] = "Barthélemy, Barthélémy, Bartholomé, Bartolomé, Emily, Nathan, Nathanaëlle";
$fetes_prenoms[25][8] = "Clovis, Loïc, Louis, Ludivine, Ludovic, Ludwig, Luigi, Luis, Luiz, Marcien, Assunta";
$fetes_prenoms[26][8] = "Césaire, César, Natacha";
$fetes_prenoms[27][8] = "Monica, Monika, Monique";
$fetes_prenoms[28][8] = "Augustin, Augustine, Hermance, Hermès, Linda";
$fetes_prenoms[29][8] = "Sabine, Sabrina, Sabryna";
$fetes_prenoms[30][8] = "Fiacre, Sacha";
$fetes_prenoms[31][8] = "Aristide";
$fetes_prenoms[1][9] = "Giles, Gilles, Josué";
$fetes_prenoms[2][9] = "Ingrid";
$fetes_prenoms[3][9] = "Graig, Greg, Grégoire, Gregor, Grégori, Grégory";
$fetes_prenoms[4][9] = "Iris, Marin, Moïse, Moshé, Rosalie";
$fetes_prenoms[5][9] = "Bertrand, Raissa, Raïssa";
$fetes_prenoms[6][9] = "Donatienne, Eva, Éva, Ève, Évelyne";
$fetes_prenoms[7][9] = "Régina, Régine, Reine, Réjane";
$fetes_prenoms[8][9] = "Adrian, Adriana, Adriane, Adrianna, Adrianne, Adrien, Adrienne, Nativité ND";
$fetes_prenoms[9][9] = "Alain, Alan, Allan, Allen, Glenn, Omer";
$fetes_prenoms[10][9] = "Aubert, Inès";
$fetes_prenoms[11][9] = "Adelphe, Daphne, Daphné, Daphnée, Daphney, Glen, Vinciane, Vincianne";
$fetes_prenoms[12][9] = "Apollinaire";
$fetes_prenoms[13][9] = "Aimé, Amé, Amy";
$fetes_prenoms[14][9] = "Materne, Sainte Croix";
$fetes_prenoms[15][9] = "Alfredo, Dolores, Dolorès, Lola, Lolita, Roland";
$fetes_prenoms[16][9] = "Abondance, Cyprien, Édith, Ludmilla, Régnault";
$fetes_prenoms[17][9] = "Hildegarde, Lambert, Réginald, Renald, Rénald, Renaud, Renauld, Renault, Ronald";
$fetes_prenoms[18][9] = "Ariane, Arianne, Marie-Line, Marilyne, Nadège, Nadia, Nadja, Sonia, Vera, Véra";
$fetes_prenoms[19][9] = "Amélie, Amély, Émilie, Gabriel";
$fetes_prenoms[20][9] = "Davy, Eustache, Kim";
$fetes_prenoms[21][9] = "Déborah, Mathew, Mathieu, Matt, Matteo, Matthew, Matthieu";
$fetes_prenoms[22][9] = "Maurice, Mauricette, Mauricio, Maurizio, Morvan";
$fetes_prenoms[23][9] = "Constant";
$fetes_prenoms[24][9] = "Andoche, Thècle";
$fetes_prenoms[25][9] = "Hermann";
$fetes_prenoms[26][9] = "Côme, Damien";
$fetes_prenoms[27][9] = "Vincent de P.";
$fetes_prenoms[28][9] = "Venceslas, Wenceslas";
$fetes_prenoms[29][9] = "Gaby, Michaël, Michal, Michel, Michèle, Michelle, Mickaël, Miguel, Mikaël, Mike, Rafael, Rafaelle, Raphaël, Raphaële, Raphaëlle";
$fetes_prenoms[30][9] = "Géronima, Jérôme";
$fetes_prenoms[1][10] = "Ariel, Ariele, Arielle, Eurielle, Thérèse E.J., Uriel, Urielle";
$fetes_prenoms[2][10] = "Léger";
$fetes_prenoms[3][10] = "Blanche, Candide, Gérard, Gerardo";
$fetes_prenoms[4][10] = "Aure, Auriane, Aurianne, Bianca, Fr. d'Assise, France, Francelin, Franceline, Francesco, Francette, Francine, Francis, Francisco, Francisque, Franck, François, Frank, Frankie, Frantz, Franz, Oriane, Orianne";
$fetes_prenoms[5][10] = "Bluette, Capucine, Cerise, Dahlia, Fleur, Hortense, Jasmine, Myrtille, Pâquerette, Pervenche, Placide, Placie, Violaine, Violette";
$fetes_prenoms[6][10] = "Bruno";
$fetes_prenoms[7][10] = "Gustave, Serge, Sergine, Sergio";
$fetes_prenoms[8][10] = "Eduin, Pélage, Pélagie, Sibylle";
$fetes_prenoms[9][10] = "Denis, Dennis, Denny, Denys, Sara, Sarah, Sibille";
$fetes_prenoms[10][10] = "Elric, Ghislain, Ghislaine";
$fetes_prenoms[11][10] = "Firmin, Soledad";
$fetes_prenoms[12][10] = "Fred, Séraphin, Séraphine, Wilfrid, Wilfried";
$fetes_prenoms[13][10] = "Géraud";
$fetes_prenoms[14][10] = "Calliste, Céleste, Énora, Gwendoline, Juste";
$fetes_prenoms[15][10] = "Aurèle, Aurélia, Aurélie, Térésa, Thérèse";
$fetes_prenoms[16][10] = "Edwige, Gaëla, Gaëlla, Perlette";
$fetes_prenoms[17][10] = "Baudoin, Baudouin, Solenne, Soline";
$fetes_prenoms[18][10] = "Aimable, Genn, Guewen, Gwenn, Gwennaig, Luc, Luca, Lucas, Morgan, Morgane";
$fetes_prenoms[19][10] = "René, Renée";
$fetes_prenoms[20][10] = "Adeline, Aline, Line";
$fetes_prenoms[21][10] = "Céline, Ursula, Ursule";
$fetes_prenoms[22][10] = "Élodie, Salomé";
$fetes_prenoms[23][10] = "Jean de Cap.";
$fetes_prenoms[24][10] = "Evrard, Florentin, Magloire";
$fetes_prenoms[25][10] = "Crépin, Daria, Doria, Enguerran";
$fetes_prenoms[26][10] = "Dimitri, Évariste";
$fetes_prenoms[27][10] = "Emeline, Émeline";
$fetes_prenoms[28][10] = "Jude, Mona, Simon, Thaddée";
$fetes_prenoms[29][10] = "Narcisse";
$fetes_prenoms[30][10] = "Bienvenue, Heidi";
$fetes_prenoms[31][10] = "Quentin, Wolfgang";
$fetes_prenoms[1][11] = "Drel, Harald, Harold, Mathurin, Toussaint, Toussainte";
$fetes_prenoms[2][11] = "Défunts";
$fetes_prenoms[3][11] = "Gwenaël, Gwénaël, Gwenaëlle, Gwénaëlle, Gwennaëlle, Ganaëlle, Huber, Hubert, Huberte";
$fetes_prenoms[4][11] = "Aymeric, Carl, Carlos, Charles, Charlette, Charley, Charlie, Charly, Emeric, Émeric, Eméric, Emerick, Imré, Jesse, Jessica, Jessie, Jessika, Jessy, Jessyca, Jessyka, Vital";
$fetes_prenoms[5][11] = "Élisa, Élise, Elissa, Élizabeth, Silvia, Sylvette, Sylvia, Sylviane, Sylvianne, Sylvie, Zac, Zacharie, Zachary, Zack";
$fetes_prenoms[6][11] = "Berthille, Bertil, Bertille, Léo, Léonard, Leonardo, Winnoc";
$fetes_prenoms[7][11] = "Caren, Carine, Ernest, Ernestine, Ernie, Karelle, Karen, Karin, Karina, Karine, Karren";
$fetes_prenoms[8][11] = "Clair, Geoffrey, Geoffroy, Godefroy";
$fetes_prenoms[9][11] = "Dora, Sybil, Théo, Théodore";
$fetes_prenoms[10][11] = "Léon, Léone, Léonilde, Léontine, Lionel, Lionnel, Noé";
$fetes_prenoms[11][11] = "Martin, Vérane";
$fetes_prenoms[12][11] = "Christian, Christin, Cristian, Tristan";
$fetes_prenoms[13][11] = "Brice, Diégo, Diègo, Killian";
$fetes_prenoms[14][11] = "Sidoine, Sidonie";
$fetes_prenoms[15][11] = "Albert, Alberta, Alberte, Albertine, Alberto, Arthur, Artur, Arturo, Léopold, Léopoldine, Malo, Victoire";
$fetes_prenoms[16][11] = "Daisy, Gertrude, Margaret, Margarita, Margaux, Margot, Marguerite, Omar, Otmar";
$fetes_prenoms[17][11] = "Babette, Bettina, Betty, Élisabeth, Elsa, Elsy, Hilda, Leslie, Lesly, Lily, Lisa, Lisbeth, Lise, Lisette, Liza, Lizzie";
$fetes_prenoms[18][11] = "Aude";
$fetes_prenoms[19][11] = "Tanguy";
$fetes_prenoms[20][11] = "Edma, Edmée, Edmond, Octave, Octavie";
$fetes_prenoms[21][11] = "Gélase, Prés. Marie";
$fetes_prenoms[22][11] = "Cécile, Cécilia, Célia, Philémon, Sheila";
$fetes_prenoms[23][11] = "Clément, Clémentine, Félicia, Félicie, Rachilde";
$fetes_prenoms[24][11] = "Augusta, Flora, Flore";
$fetes_prenoms[25][11] = "Cathel, Catherine, Katel";
$fetes_prenoms[26][11] = "Conrad, Delphine, Kurt";
$fetes_prenoms[27][11] = "Astrid, Séverin, Séverine";
$fetes_prenoms[28][11] = "Jacq. de M.";
$fetes_prenoms[29][11] = "Saturnin";
$fetes_prenoms[30][11] = "André, Andréa, Andréane, Andréanne, Andréas, Andrée, Andrée-Anne, Andrei, Andréi, Andrés, Andrew, Andry";
$fetes_prenoms[1][12] = "Alar, Alaric, Alena, Alrick, Éloi, Éloise, Éloïse, Florence, Nahum, Natalie, Tudal, Tugdual";
$fetes_prenoms[2][12] = "Vivian, Viviana, Viviane";
$fetes_prenoms[3][12] = "Fran. Xavier, François-Xavier, Xavier";
$fetes_prenoms[4][12] = "Ada, Adnette, Barban, Barbara, Barbe, Barberine, Barbie";
$fetes_prenoms[5][12] = "Gérald, Géraldine";
$fetes_prenoms[6][12] = "Colin, Nicholas, Nick, Nickolas, Nico, Nicola, Nicolas, Niko, Nikola, Nikolas";
$fetes_prenoms[7][12] = "Ambre, Ambroise";
$fetes_prenoms[8][12] = "Elfi, Elfried, Im. Concept.";
$fetes_prenoms[9][12] = "P. Fourier";
$fetes_prenoms[10][12] = "Romaric";
$fetes_prenoms[11][12] = "Daniel, Daniela, Danièle, Danielle, Danitza, Danny, Dany";
$fetes_prenoms[12][12] = "Chantal, Chantale, Corentin, Jeanne FC, Paquita";
$fetes_prenoms[13][12] = "Aurore, Joceline, Jocelyn, Jocelyne, Josse, Josselin, Josseline, Luce, Lucette, Lucia, Lucie, Lucy";
$fetes_prenoms[14][12] = "Odile";
$fetes_prenoms[15][12] = "Christiane, Christianne, Ninon";
$fetes_prenoms[16][12] = "Adélaïde, Alice, Alizée";
$fetes_prenoms[17][12] = "Gaël, Gaële, Gaëlle, Judicaël, Tessa";
$fetes_prenoms[18][12] = "Briac, Gatien, Gratienne, Robin";
$fetes_prenoms[19][12] = "Urbain";
$fetes_prenoms[20][12] = "Abraham, Isaac, Jacob, Joy, Théophile, Zéphirin, Zéphyrin";
$fetes_prenoms[21][12] = "Pierre Can.";
$fetes_prenoms[22][12] = "Fra. Xavière, Xavière";
$fetes_prenoms[23][12] = "";
$fetes_prenoms[24][12] = "Adèle, Delphin";
$fetes_prenoms[25][12] = "Emanuel, Emmanuel, Emmanuèle, Emmanuelle, Manoël, Manuel, Nello, Noël, Noëla, Noëlle";
$fetes_prenoms[26][12] = "Esteban, Étienne, Fannie, Fanny, Stef, Stefan, Stéfan, Stéphane, Stéphanie, Stéphanne, Stéve";
$fetes_prenoms[27][12] = "Fabiola, Jean Apôtre, Jehan";
$fetes_prenoms[28][12] = "Gaspard, Innocent, Innocents";
$fetes_prenoms[29][12] = "David";
$fetes_prenoms[30][12] = "Roger";
$fetes_prenoms[31][12] = "Colombe, Mélanie, Melina, Sylvestre";
/*

function dateFR($args, $timestamp = NULL)
{

	if ($timestamp == NULL)
	// si le timestamp est null...
	{$timestamp = time();
	//On prend le timestamp actuel
	}
	$joursFR = array('lundi','mardi','mercredi','jeudi','vendredi','samedi', 'dimanche');
	// Jours en français
	$joursEN = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday', 'Sunday'),
	// Jours en Anglais
	$moisFR = array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'),
	// Les mois en Français
	$moisEN = array('January','February','March','April','May','June','July','August','September','October','November','December'),
	//Les mois en Anglais
	$joursAbregeFR = array('Lun','Mar','Mer','Jeu','Ven','Sam', 'Dim'),
	// Jours abrégés en français
	$joursAbregeEN = array('Mon','Tue','Wed','Thu','Fri','Sat', 'Sun'),
	// Jours abrégés en Anglais
	$moisAbregeFR = array('Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'),
	// Les mois abrégés en Français
	$moisAbregeEN = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
	//Les mois abrégés en Anglais
	$val = date($args, $timestamp),
	// On éxécute la fonction date avec les arguments
	$val = str_replace($joursEN, $joursFR, $val),
	// Si il y a des jours en Anglais dans la variable retournnée par la fonction Date(), bah on les traduits en français
	$val = str_replace($joursAbregeEN, $joursAbregeFR, $val),
	// Si il y a des mois en Anglais dans la variable retournée par la fonction Date(), bah on les trduits en français
	$val = str_replace($moisEN, $moisFR, $val),
	// Si il y a des mois en Anglais dans la variable retournée par la fonction Date(), bah on les trduits en français
	$val = str_replace($moisAbregeEN, $moisAbregeFR, $val),
	// Si il y a des mois en Anglais dans la variable retournée par la fonction Date(), bah on les trduits en français
	return $val,
	// Pour finir, bah on retourne la variable avec les jours et les mois traduits de l'anglais au français
}*/

function GetFete()
{	

	global $fetes_prenoms;		

	$prenoms=$fetes_prenoms[date('j')][date('n')]; 	
	return $prenoms;
}

function GetFeteDate($d,$m)
{	

	global $fetes_prenoms;		

	$prenoms=$fetes_prenoms[$d][$m]; 	
	return $prenoms;
}

function GetFetePrenom($prenom)
{	

	global $fetes_prenoms;	

	$tFete=Array();		

	for($i=1;$i<=count($fetes_prenoms);$i++)	
	{		

		for($j=1;$j<=count($fetes_prenoms[$i]);$j++)		
		{			

			if(isset($fetes_prenoms[$i][$j]))			
			{				
				$tFete=split(", ",$fetes_prenoms[$i][$j]);				
				//print_r($tFete);				

				for($k=0;$k<count($tFete);$k++)				
				{					

					if ($prenom==$tFete[$k])						
						return $i.'/'.$j;				
				}			
			}		
		}	
	}	
	return "";
}

function GetPrenomsDates()
{	
	$t=Array();	

	global $fetes_prenoms;		

	for($i=1;$i<=count($fetes_prenoms);$i++)	
	{		

		for($j=1;$j<=count($fetes_prenoms[$i]);$j++)		
		{			

			if(isset($fetes_prenoms[$i][$j]))			
			{				
				$tFete=split(", ",$fetes_prenoms[$i][$j]);				
				//print_r($tFete);				

				for($k=0;$k<count($tFete);$k++)				
				{					

					if ($tFete[$k]) $t[$tFete[$k]]=$i.'/'.$j;				
				}			
			}		
		}	
	}	
	ksort($t);	
	return $t;
}