SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `ayet` (
  `ayet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tarih` int(10) unsigned NOT NULL,
  `sure_no` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  `sure_adi` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `ayet_meal` varchar(5000) COLLATE utf8_turkish_ci NOT NULL,
  `ek_not` varchar(5000) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`ayet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=440 ;

CREATE TABLE IF NOT EXISTS `ayet_tag` (
  `ayet_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `gunun_ayeti` (
  `ulasilan_sayi` int(11) NOT NULL,
  `duzenlenen_tarih` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tarih` int(10) unsigned NOT NULL,
  `tag_adi` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=54 ;

CREATE TABLE IF NOT EXISTS `yoneticiler` (
  `uye_id` int(11) NOT NULL AUTO_INCREMENT,
  `uye_adi` varchar(20) COLLATE utf8_turkish_ci NOT NULL,
  `sifre` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`uye_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=2 ;
