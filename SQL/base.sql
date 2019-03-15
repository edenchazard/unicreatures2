-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2013 at 10:23 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.7ppa5~lucid1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `unicreatures`
--

--
-- Dumping data for table `creature_base_abilities`
--

INSERT INTO `creature_base_abilities` (`id`, `creature_family`, `strength`, `agility`, `speed`, `intelligence`, `wisdom`, `charisma`, `creativity`, `willpower`, `focus`) VALUES
(1, 'Blizz', 3, 2, 2, 4, 6, 7, 7, 6, 6),
(2, 'Flizzard', 2, 4, 5, 4, 2, 7, 12, 2, 4),
(3, 'Armor', 4, 2, 1, 13, 11, 4, 3, 2, 2),
(4, 'Orbit', 2, 5, 6, 7, 13, 11, 8, 6, 3),
(8, 'Melody', 2, 5, 3, 5, 3, 6, 2, 4, 1),
(9, 'Flick', 1, 6, 9, 2, 2, 3, 3, 1, 4),
(10, 'Wury', 6, 1, 1, 2, 1, 2, 16, 26, 19),
(11, 'Tye', 2, 6, 2, 3, 3, 16, 9, 5, 4),
(12, 'Twap', 2, 2, 1, 2, 2, 1, 1, 3, 2),
(13, 'Horizon', 8, 6, 10, 6, 5, 12, 7, 9, 5),
(14, 'Kyler', 10, 6, 3, 2, 1, 3, 2, 8, 5),
(15, 'Sly', 6, 13, 12, 7, 4, 2, 4, 9, 9),
(16, 'Cyber', 1, 2, 2, 19, 4, 1, 3, 2, 16),
(17, 'Aurora', 1, 3, 4, 3, 8, 15, 13, 4, 9),
(18, 'Cupid', 3, 4, 3, 4, 6, 8, 7, 4, 1),
(19, 'Stone', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(20, 'Nagi', 1, 2, 1, 3, 3, 5, 2, 1, 1),
(21, 'Mythera', 11, 10, 9, 12, 20, 7, 5, 8, 7),
(22, 'Clover', 2, 3, 3, 4, 6, 6, 10, 8, 2),
(23, 'Flarius', 9, 7, 4, 3, 5, 3, 2, 3, 1),
(24, 'Lazuli', 4, 7, 6, 3, 2, 9, 3, 1, 3),
(25, 'Zappa', 1, 5, 1, 4, 2, 3, 4, 2, 3),
(26, 'Guille', 2, 3, 3, 9, 5, 6, 2, 3, 2),
(27, 'McPuppet', 3, 21, 2, 8, 17, 26, 22, 18, 19),
(28, 'Jerial', 1, 7, 12, 3, 2, 4, 3, 2, 2),
(29, 'Eclipse', 6, 8, 7, 8, 14, 8, 6, 9, 10),
(30, 'Caprine', 2, 3, 3, 5, 3, 4, 3, 2, 2),
(31, 'Blossom', 1, 3, 2, 7, 8, 13, 12, 9, 14),
(32, 'Waddles', 2, 2, 3, 2, 2, 7, 28, 9, 2),
(33, 'Anex', 5, 9, 14, 4, 3, 4, 3, 6, 3),
(34, 'Twitch', 3, 4, 8, 7, 2, 5, 8, 3, 2),
(35, 'Fennex', 2, 8, 6, 4, 5, 4, 3, 3, 3),
(36, 'Fennix', 3, 8, 6, 3, 3, 4, 4, 3, 2),
(37, 'Rumi', 1, 3, 1, 6, 6, 4, 3, 5, 5),
(38, 'Daisy', 1, 3, 1, 2, 5, 2, 3, 1, 1),
(39, 'Lani', 9, 7, 3, 5, 10, 6, 4, 6, 5),
(40, 'Cyanne', 4, 7, 9, 4, 7, 7, 3, 4, 2),
(41, 'Zahrah', 2, 3, 2, 2, 2, 3, 6, 4, 3),
(42, 'Yarr', 3, 7, 5, 3, 2, 4, 3, 6, 2),
(43, 'Beeks', 2, 3, 5, 4, 3, 4, 5, 3, 2),
(44, 'Allure', 2, 2, 2, 2, 2, 1, 2, 2, 2),
(45, 'Ahanu', 1, 3, 7, 4, 5, 6, 5, 2, 1),
(46, 'Kaan', 2, 7, 4, 15, 12, 3, 8, 3, 2),
(47, 'Freyr', 2, 9, 7, 13, 15, 12, 13, 12, 6),
(48, 'Echo', 4, 12, 9, 8, 8, 9, 10, 6, 2),
(49, 'Jolie', 4, 3, 3, 3, 4, 13, 5, 9, 6),
(50, 'Muse', 2, 3, 6, 4, 15, 4, 8, 3, 5),
(51, 'Dualis', 3, 4, 4, 4, 3, 3, 4, 3, 4),
(52, 'Tsuru', 1, 8, 2, 3, 3, 1, 7, 2, 1),
(53, 'Skip', 5, 8, 6, 7, 6, 3, 7, 5, 8),
(54, 'Jasper', 3, 5, 3, 4, 6, 11, 7, 5, 2),
(55, 'Hawk', 7, 12, 4, 4, 3, 3, 6, 8, 9),
(56, 'Scotty', 5, 6, 7, 7, 7, 3, 3, 5, 6),
(57, 'Flamenco', 2, 7, 1, 5, 4, 15, 11, 4, 8),
(58, 'Diani', 11, 6, 9, 4, 5, 9, 3, 3, 2),
(59, 'Joey', 2, 4, 2, 4, 3, 5, 2, 2, 2),
(60, 'Alban', 1, 5, 4, 3, 3, 5, 4, 3, 2),
(61, 'Kaylee', 2, 1, 3, 2, 4, 6, 7, 5, 3),
(62, 'Magos', 1, 5, 3, 2, 4, 8, 7, 3, 1),
(63, 'Bamboo', 7, 2, 2, 5, 9, 6, 3, 5, 8),
(64, 'Maia', 3, 2, 8, 7, 3, 4, 4, 8, 5),
(65, 'Khet', 7, 9, 8, 10, 14, 8, 6, 5, 4),
(66, 'Plymouth', 2, 1, 2, 3, 4, 7, 4, 5, 6),
(67, 'Perline', 1, 3, 4, 5, 9, 2, 2, 7, 3),
(68, 'Manjot', 5, 3, 3, 6, 6, 2, 4, 2, 3),
(69, 'Julian', 2, 4, 2, 3, 3, 6, 5, 1, 3),
(70, 'Aurum', 10, 8, 9, 12, 11, 6, 3, 10, 13),
(71, 'Unity', 4, 3, 3, 4, 3, 4, 7, 4, 6),
(72, 'Relity', 14, 10, 8, 3, 4, 6, 2, 4, 3),
(73, 'Capricorn', 3, 3, 2, 5, 6, 1, 6, 2, 2),
(74, 'Oasis', 1, 2, 2, 4, 8, 7, 3, 5, 5),
(75, 'Fortuna', 3, 3, 3, 4, 7, 7, 5, 5, 7),
(76, 'Dahli', 3, 5, 6, 2, 1, 2, 2, 3, 4),
(77, 'Neon', 2, 5, 3, 2, 1, 4, 6, 3, 3),
(78, 'Nivier', 1, 3, 5, 2, 3, 8, 3, 4, 5),
(79, 'Aquarius', 2, 3, 1, 10, 5, 8, 12, 2, 3),
(80, 'Tink', 1, 6, 4, 3, 2, 7, 8, 2, 1),
(81, 'Sabu', 5, 6, 5, 2, 4, 4, 2, 8, 3),
(82, 'Capricious', 8, 6, 9, 13, 2, 16, 22, 1, 1),
(83, 'Chance', 2, 3, 1, 5, 8, 4, 5, 6, 4),
(84, 'Pisces', 1, 3, 1, 4, 3, 2, 11, 2, 8),
(85, 'Puffy', 1, 5, 4, 7, 2, 7, 9, 3, 1),
(86, 'Kalay', 3, 3, 5, 11, 5, 8, 4, 2, 2),
(87, 'Aries', 4, 1, 1, 2, 2, 1, 3, 10, 5),
(88, 'Lotus', 1, 8, 8, 2, 3, 6, 4, 1, 2),
(89, 'Aello', 2, 7, 4, 6, 1, 3, 2, 5, 4),
(90, 'Silver', 6, 4, 7, 2, 1, 2, 1, 3, 4),
(91, 'Yawn', 2, 1, 1, 8, 4, 4, 3, 2, 4),
(92, 'Volk', 1, 4, 5, 5, 3, 1, 3, 3, 2),
(93, 'Dendros', 4, 7, 2, 20, 22, 4, 8, 15, 13),
(94, 'Taurus', 9, 1, 2, 2, 3, 3, 2, 12, 8),
(95, 'Rasun', 3, 3, 4, 7, 1, 2, 3, 3, 2),
(96, 'Brontide', 7, 3, 5, 3, 4, 9, 2, 1, 3),
(97, 'Dale', 3, 11, 4, 5, 2, 1, 4, 3, 2),
(98, 'Gemini', 2, 4, 6, 8, 2, 4, 3, 1, 1),
(99, 'Nevar', 2, 6, 5, 2, 3, 1, 1, 7, 5),
(100, 'Lycas', 7, 6, 6, 3, 2, 4, 3, 5, 3),
(101, 'Qiana', 2, 5, 3, 2, 4, 2, 2, 6, 3),
(102, 'Torch', 2, 12, 4, 3, 5, 3, 3, 2, 1),
(103, 'Delilah', 1, 8, 3, 4, 3, 10, 3, 5, 4),
(104, 'Kanan', 3, 11, 13, 1, 3, 5, 2, 4, 8),
(105, 'Cancer', 3, 2, 1, 2, 4, 2, 1, 5, 4),
(106, 'Felic', 4, 3, 6, 9, 8, 5, 8, 7, 7),
(107, 'Leo', 6, 5, 5, 4, 3, 11, 7, 5, 5),
(108, 'Indra', 3, 9, 2, 6, 5, 9, 3, 6, 7),
(109, 'Neptune', 7, 9, 12, 3, 7, 8, 2, 3, 6),
(110, 'Virgo', 2, 3, 5, 4, 9, 11, 5, 7, 6),
(111, 'Qirin', 9, 7, 9, 10, 8, 6, 6, 12, 13),
(112, 'Lamiel', 8, 9, 10, 6, 7, 7, 8, 5, 5),
(113, 'Leaf', 6, 7, 8, 7, 9, 11, 12, 6, 2),
(114, 'Tictoc', 7, 3, 4, 10, 3, 4, 3, 5, 7),
(115, 'Mikhal', 4, 5, 2, 10, 2, 4, 11, 2, 5),
(116, 'Lilliana', 3, 6, 2, 7, 8, 9, 2, 4, 5),
(117, 'Libra', 5, 6, 4, 7, 8, 6, 3, 4, 7),
(118, 'Pesdir', 3, 4, 5, 1, 1, 2, 4, 1, 3),
(119, 'Scorpio', 5, 2, 4, 4, 2, 2, 2, 7, 6),
(120, 'Sagittarius', 5, 7, 4, 7, 8, 5, 4, 5, 6),
(121, 'Bastet', 5, 11, 7, 11, 9, 7, 7, 3, 3),
(122, 'Sekmet', 5, 4, 3, 8, 10, 4, 6, 4, 8),
(123, 'Garnet', 6, 5, 5, 7, 5, 6, 5, 5, 5),
(124, 'Nifer', 7, 5, 0, 3, 4, 5, 4, 6, 4),
(125, 'Peppo', 1, 3, 3, 2, 2, 2, 3, 2, 3),
(126, 'Amethyst', 4, 10, 2, 3, 2, 4, 12, 3, 2),
(127, 'Rex', 14, 10, 5, 8, 4, 9, 2, 3, 1),
(128, 'Nanook', 10, 2, 4, 5, 3, 2, 1, 3, 4),
(129, 'Aquamarine', 10, 7, 5, 6, 5, 7, 4, 6, 7),
(130, 'Maglia', 7, 4, 0, 5, 4, 6, 5, 3, 1),
(131, 'Leeto', 3, 4, 12, 2, 3, 6, 2, 2, 4),
(132, 'Diamond', 8, 2, 4, 5, 2, 10, 2, 8, 4),
(133, 'Harlequin', 2, 5, 4, 4, 6, 8, 7, 4, 6),
(134, 'Emerald', 10, 1, 2, 3, 7, 2, 2, 5, 4),
(135, 'Nimi', 0, 0, 0, 0, 7, 6, 1, 2, 1),
(136, 'Nishiki', 4, 2, 4, 4, 5, 6, 7, 4, 3),
(137, 'Ori', 4, 2, 4, 8, 8, 4, 8, 2, 2),
(138, 'Pearl', 2, 2, 2, 8, 7, 3, 4, 6, 7),
(139, 'Seamore', 7, 8, 2, 3, 5, 4, 3, 8, 3),
(140, 'Creme', 7, 11, 8, 5, 4, 9, 7, 2, 5),
(141, 'Adamant', 10, 4, 3, 6, 8, 9, 4, 8, 7),
(142, 'Arbor', 11, 5, 5, 8, 4, 4, 4, 6, 8),
(143, 'Knidley', 2, 5, 4, 5, 4, 3, 6, 3, 4),
(144, 'Hartley', 3, 4, 2, 5, 2, 6, 4, 2, 5),
(145, 'Ruby', 4, 8, 8, 11, 7, 9, 5, 7, 6),
(146, 'Peridot', 2, 6, 7, 5, 4, 3, 10, 8, 7),
(147, 'Tenny', 4, 3, 0, 4, 5, 8, 8, 4, 6),
(148, 'Sapphire', 7, 5, 2, 4, 3, 1, 1, 12, 15),
(149, 'Tremolo', 1, 4, 3, 4, 5, 5, 5, 2, 1),
(150, 'Hoppi', 8, 2, 6, 3, 4, 4, 2, 5, 2),
(151, 'Opal', 3, 3, 6, 2, 3, 4, 9, 7, 5),
(152, 'Heifer', 4, 3, 2, 6, 8, 3, 2, 5, 1),
(153, 'Lief', 5, 6, 8, 7, 11, 12, 5, 3, 2),
(154, 'Nautus', 0, 2, 2, 9, 2, 0, 1, 2, 1),
(155, 'Eos', 5, 11, 7, 3, 5, 9, 12, 3, 2),
(156, 'Zamard', 3, 4, 7, 8, 4, 4, 3, 1, 4),
(157, 'Dusa', 5, 7, 7, 6, 2, 6, 1, 3, 1),
(158, 'Xylder', 8, 6, 5, 8, 8, 5, 4, 3, 7),
(159, 'Sturm', 10, 5, 2, 5, 2, 10, 2, 5, 2),
(160, 'Drang', 2, 5, 2, 10, 5, 2, 5, 10, 5),
(161, 'Halcyon', 5, 5, 5, 5, 5, 5, 5, 5, 5),
(162, 'Topaz', 3, 5, 4, 5, 5, 2, 3, 4, 3),
(163, 'Joy', 3, 4, 5, 7, 3, 9, 6, 3, 4),
(164, 'Turquoise', 5, 2, 5, 4, 3, 2, 7, 7, 7),
(165, 'Squee', 5, 5, 5, 5, 5, 5, 5, 5, 5),
(166, 'Vulkan', 12, 8, 9, 8, 6, 5, 4, 5, 6),
(167, 'Wasbee', 2, 4, 6, 4, 2, 2, 4, 6, 2),
(168, 'Esme', 3, 7, 5, 4, 6, 8, 5, 3, 2),
(169, 'Mao', 2, 3, 5, 2, 2, 5, 3, 3, 3),
(170, 'Xithus', 5, 4, 2, 5, 2, 3, 4, 2, 5),
(171, 'Yamaya', 2, 4, 4, 1, 1, 3, 3, 1, 2),
(172, 'Terran', 10, 2, 2, 5, 5, 2, 1, 3, 5),
(173, 'Mowse', 4, 10, 11, 12, 2, 4, 4, 3, 5),
(174, 'Cardi', 2, 7, 6, 5, 4, 2, 2, 4, 4),
(175, 'Inferno', 6, 7, 7, 4, 3, 7, 8, 4, 4),
(176, 'Ulle', 1, 3, 2, 2, 1, 3, 3, 1, 2),
(177, 'Shairi', 2, 7, 5, 6, 6, 6, 6, 2, 4),
(178, 'Columbia', 8, 4, 4, 3, 2, 9, 8, 2, 3),
(179, 'Zoumper', 4, 5, 7, 3, 2, 3, 4, 2, 6),
(180, 'Kingda', 6, 7, 2, 2, 4, 4, 5, 1, 5),
(181, 'Fluff', 5, 3, 3, 3, 2, 4, 2, 5, 5),
(182, 'Froudo', 11, 14, 10, 5, 2, 3, 7, 3, 2),
(183, 'Bordeaux', 4, 2, 2, 1, 1, 5, 5, 1, 4),
(184, 'Wintergreen', 3, 5, 6, 3, 4, 7, 9, 7, 3),
(185, 'Rend', 10, 1, 9, 1, 2, 3, 1, 3, 4),
(186, 'Mend', 8, 6, 5, 9, 4, 5, 10, 3, 4),
(187, 'Howl', 6, 8, 9, 5, 4, 6, 3, 9, 6),
(188, 'Viggie', 3, 4, 5, 4, 2, 7, 4, 6, 2),
(189, 'Quetz', 3, 2, 4, 2, 4, 2, 4, 5, 2),
(190, 'Weyekin', 6, 5, 4, 8, 11, 6, 8, 5, 5),
(191, 'Denuo', 5, 7, 8, 3, 3, 16, 20, 8, 16),
(192, 'Soar', 2, 3, 5, 5, 7, 3, 7, 9, 6),
(193, 'Arishia', 6, 9, 5, 7, 9, 5, 3, 9, 5),
(194, 'Erina', 3, 4, 3, 12, 10, 3, 11, 5, 6),
(195, 'Darini', 14, 10, 11, 3, 8, 9, 9, 6, 9),
(196, 'Sakuya', 3, 4, 6, 4, 2, 7, 6, 5, 5);