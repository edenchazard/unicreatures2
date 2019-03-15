<?php
/***** SETTINGS FOR UC / environment
		PLEASE READ INSTRUCTIONS CAREFULLY BEFORE MODIFYING
		OR YOU'LL DIE FROM HAMSTERS
		-42
*******/

//defaults
env::set('caretaker_eggs_to_show', 5);
env::set('noble_min_time_pass', 86400); //seconds
env::set('exalted_min_time_pass', 360000); //seconds
env::set('noble_chance', 100);
env::set('exalted_chance', 315);
env::set('train_energy', 50);
env::set('exploring_energy', 50);
env::set('rare_component_chance', 0);
env::set('component_trading_fee', 0.7); //70% ratio
env::set('exaltation_required_points', 1000);
env::set('noblization_required_points', 500);
env::set('building_material_chance', 500);
env::set('gather_chance', 26);
env::set('own_care', 1); //how many pts to give for caring
				  //for your own thing

//TODO
//is user logged in
	if(is_logged_in()){
		switch($user->subclass){
			case 'Traveler':
				env::manipulate('explore_energy', '+50');
				env::manipulate('explore_regen_interval', '-30%');
				break;
			case 'Adventurer':
				env::manipulate('explore_energy', '+20');
				env::manipulate('explore_regen_interval', '-40%');
				break;
			case 'Rogue':
				env::manipulate('exalted_chance', '-10%');
				env::manipulate('noble_chance', '-18%');
				env::set('noble_min_time_pass', 0);
				break;
			case 'Atrologer':
				env::manipulate('exalted_chance', '-10%');
				env::manipulate('noble_chance', '-15%');
				env::set('exalted_min_time_pass', 0);
				break;
			case 'Biologist':
				break;
			case 'Giver':	
				env::manipulate('own_care', '+1');
				env::manipulate('train_energy', '+15');
				env::manipulate('explore_energy', '+15');
				env::manipulate('caretaker_eggs_to_show', '+2');
				break;
			/* end bard class
			   begin crafter class */
			case 'Haggler':
				env::manipulate('building_material_chance', '-40%');
				env::set('rare_component_chance', 700);
				env::set('component_trading_fee', 1);
				break;
			case 'Architect':
				env::manipulate('building_material_chance', '-50%');
				env::manipulate('land_plot_material_cost', '-20%');
				env::set('rare_component_chance', 700);
				break;
			//lumberjack, mason, metalworker, supplier
			case 'Seeker':
				env::manipulate('building_material_chance', '-40%');
				env::set('rare_component_chance', 280);
				break;
			/* end crafter class
			   begin peacekeeper class */
			case 'Achiever':
				env::manipulate('train_energy', '+25');
			//	env::manipulate('explore_energy', '+25');
				env::manipulate('own_care', '+1');
				break;
		}
	}
?>