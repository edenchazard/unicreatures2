INSERT INTO explore_stories
SELECT `id`, 2, title, description, explore_types.history,
(SELECT creatureID FROM creatures_db WHERE family_name = creature_1_family AND stage = creature_1_stage),
creature_1_option,
(SELECT creatureID FROM creatures_db WHERE family_name = creature_2_family AND stage = creature_2_stage),
creature_2_option,
(SELECT creatureID FROM creatures_db WHERE family_name = creature_3_family AND stage = creature_3_stage),
creature_3_option FROM `explore_types` WHERE area_type='sea' 

UPDATE `user_owned_items` SET `male_gen_x`=20000,`female_gen_x`=20000,`component_bag`=20000,`cryogenic_freeze_spray`=20000,`vigor_potion`=20000,`moxie_potion`=20000,`vitality_potion`=20000,`refresh_potion`=20000,`time_warp_watch`=20000,`defrosting_torch`=20000,`story_parchment`=20000,`normalize_potion`=20000,`elixir_of_exaltation`=20000,`elixir_of_nobility`=20000,`profession_scroll`=20000
UPDATE `user_owned_components` SET ancientberry = 20000;
UPDATE `user_owned_components` SET `astralune` = 20000;
UPDATE `user_owned_components` SET `auraglass` = 20000;
UPDATE `user_owned_components` SET `bluemaple` = 20000;
UPDATE `user_owned_components` SET `echoberry` = 20000;
UPDATE `user_owned_components` SET `essentia` = 20000;
UPDATE `user_owned_components` SET `heartwater` = 20000;
UPDATE `user_owned_components` SET `lifepowder` = 20000;
UPDATE `user_owned_components` SET `meadowgem` = 20000;
UPDATE `user_owned_components` SET `moonruby` = 20000;
UPDATE `user_owned_components` SET `riverstone` = 20000;
UPDATE `user_owned_components` SET `seamelon` = 20000;
UPDATE `user_owned_components` SET `skypollen` = 20000;
UPDATE `user_owned_components` SET `starweave` = 20000;
UPDATE `user_owned_components` SET `sunnyseed` = 20000;
UPDATE `user_owned_components` SET `timeshard` = 20000;
UPDATE `user_owned_components` SET `treescent` = 20000;
UPDATE `user_owned_components` SET `watervine` = 20000;
UPDATE `user_owned_components` SET `whiteroot` = 20000;
UPDATE `user_owned_components` SET `wood` = 20000;
UPDATE `user_owned_components` SET `stone` = 20000;
UPDATE `user_owned_components` SET `metal` = 20000;
UPDATE `user_owned_components` SET `supplies` = 20000;
UPDATE `user_owned_components` SET `gemstone` = 20000;
UPDATE `user_owned_components` SET `tree_seeds` = 20000;
UPDATE `user_owned_components` SET `spirit_stones` = 20000;
UPDATE `user_owned_components` SET `earth_orb` = 20000;
UPDATE `user_owned_components` SET `fire_orb` = 20000;
UPDATE `user_owned_components` SET `water_orb` = 20000;
UPDATE `user_owned_components` SET `wind_orb` = 20000;
UPDATE `user_owned_components` SET `earth_shard` = 20000;
UPDATE `user_owned_components` SET `fire_shard` = 20000;
UPDATE `user_owned_components` SET `water_shard` = 20000;
UPDATE `user_owned_components` SET `wind_shard` = 20000;

SELECT cl_to, cl_sortkey, SUBSTRING( cl_to, 8, length( cl_to ) -1 ) AS writer
FROM `categorylinks`
WHERE LOCATE( 'Writer', cl_to ) >0
LIMIT 120 , 30