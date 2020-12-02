DROP DATABASE IF EXISTS registration;
CREATE DATABASE registration;
use `registration`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET foreign_key_checks = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`blog_id`, `subject`, `description`, `tags`, `user_id`, `date`) VALUES
(4, 'Messi', 'Lionel Messi is a soccer player with FC Barcelona and the Argentina national team. He has established records for goals scored and won individual awards en route to worldwide recognition as one of the best players in soccer. Who Is Lionel Messi? Luis Lionel Andres (“Leo”) Messi is an Argentinian soccer player who plays forward for the FC Barcelona club and the Argentina national team. At the age of 13, Messi moved from Argentina to Spain after FC Barcelona agreed to pay for his medical treatments. There he earned renown as one of the greatest players in history, helping his club win more than two dozen league titles and tournaments. In 2012, he set a record for most goals in a calendar year, and in 2019, he was named Europes Ballon dOr winner for the sixth time. Early Life Messi was born on June 24, 1987, in Rosario, Argentina. As a young boy, Messi tagged along when his two older brothers played soccer with their friends, unintimidated by the bigger boys. At the age of 8, he was recruited to join the youth system of Newells Old Boys, a Rosario-based club. Recognizably smaller than most of the kids in his age group, Messi was eventually diagnosed by doctors as suffering from a hormone deficiency that restricted his growth. Messis parents, Jorge and Ceclia, decided on a regimen of nightly growth-hormone injections for their son, though it soon proved impossible to pay several hundred dollars per month for the medication.', 'GOAT, The Best, Argentina, Soccer, Messi', 4, '2020-11-29'),
(5, 'Cristiano Ronaldo', 'Cristiano Ronaldo is a professional soccer player who has set records while playing for the Manchester United, Real Madrid and Juventus clubs, as well as the Portuguese national team. Who Is Cristiano Ronaldo? Cristiano Ronaldo dos Santos Aveiro is a Portuguese soccer superstar. By 2003 — when he was just 16 years old — Manchester United paid £12 million (over $14 million U.S. dollars) to sign him, a record fee for a player of his age. In the 2004 FA Cup final, Ronaldo scored Manchesters first three goals and helped them capture the championship. He set a franchise record for goals scored in 2008, before Real Madrid paid a record $131 million for his services the following year. Among his many accomplishments, he has won a record-tying five Ballon dOr awards for player of the year, and led Portugal to an emotional victory in the 2016 European Championship. In July 2018, Ronaldo embarked on a new phase of his career by signing with Italian Serie A club Juventus.', 'Portugal, Ronaldo, Soccer', 3, '2020-11-29'),
(6, 'Ronaldinho', 'Soccer superstar Ronaldinho was a member of Brazils 2002 World Cup championship team and twice won the FIFA World Player of the Year award. Synopsis Brazilian soccer star Ronaldinho came from a family of soccer players to reach the pinnacle of success in the sport. After a celebrated youth career, Ronaldinho became a key member of the Brazilian team that won the 2002 World Cup. He has played for clubs in Brazil, France, Spain and Italy, and has twice been named FIFA World Player of the Year. Early Life Ronaldinho was born Ronaldo de Assis Moreira on March 21, 1980, in Porto Alegre, Brazil. His father, João Moreira, was a former professional soccer player who also worked as a welder in a shipyard, and his mother, Miguelina de Assis, was a cosmetics saleswoman who later became a nurse. Ronaldinhos older brother, Roberto Assis, was also a professional soccer player; Ronaldinho was surrounded by soccer from the day he was born. "I come from a family where soccer has always been very present," he said. "My uncles, my father and my brother were all players. Living with that kind of background, I learned a great deal from them. I tried to devote myself to it more and more with the passage of time."', 'Magician, Brazil, Freestyle, Ronaldinho', 5, '2020-11-29'),
(7, 'Maradona', 'Diego Maradona was an Argentinean soccer legend who was widely regarded as one of the best players of all time. Maradona led club teams to championships in Argentina, Italy and Spain, and famously starred for the Argentinean team that won the 1986 World Cup. However, the soccer legends career was marred by a pair of high-profile suspensions for drug use, and he has often battled health problems in retirement."', 'Magician, Argentina, Freestyle, Maradona', 4, '2020-11-29'),
(8, 'Iniesta', 'Andrés Iniesta, in full Andrés Iniesta Luján, (born May 11, 1984, Fuentealbilla, Spain), Spanish football (soccer) player who helped his country win the Euro title in 2008 and 2012 and the 2010 World Cup; it was the first time a national squad had captured three consecutive major world championships."', 'Spain, Freestyle, Iniesta', 3, '2020-11-30'),
(9, 'Xavi', 'Xavi, byname of Xavier Hernández Creus, (born January 25, 1980, Terrassa, Spain), Spanish football (soccer) player who was widely regarded as one of the best midfielders in the world in the early 21st century."', 'Magician, Spain, Freestyle, Xavi', 5, '2020-11-30'),
(10, 'Luiz Surarez', 'Luis Suarez is the star striker of the Uruguayan national soccer team who went on to play for Ajax and Liverpool before he joined the Spanish giants FC Barcelona. He came into prominence for all the wrong reasons including biting Italian defender, Giorgio Chiellini, during a world cup match in 2014. However, he is still considered to be one of the best strikers in the world and has been the European Top Scorer and winner of the ‘Golden Boot’ award twice. His aggressive nature on the field has been attributed to his harsh upbringing, ridden with poverty, and a broken family where his father walked out leaving his mother to bring up seven siblings. His childhood sweetheart, whom he eventually married, has been his saving grace that had a positive bearing on his life and put him back on track. Today he is one of the highest paid footballers in the world with a net worth estimated at over $ 40 million."', 'Forward, Uruguay, Suarez', 4, '2020-11-30'),
(11, 'Pele', 'Edson Arantes Do Nascimento Pele, who took the name Pele, was born October 23, 1940, in Tres Coracoes, Brazil, the son of a soccer player. His fathers coaching paid off, for when he was 11 he played for his first soccer team, that of the town of Bauru, Brazil. He moved up in competition with outstanding play, and when he was 15 he was playing for the team from the village of Santos. He soon received broader exposure when he was loaned to the Vasco da Gama team in Rio di Janeiro."', 'Magician, Brazil, Freestyle, Pele', 3, '2020-11-28'),
(12, 'Deco', 'A professional footballer Anderson Luís de Souza, aka Deco, operates as an attacking midfielder for Fluminense. He was born on 27 August 1977 in São Bernardo do Campo. Deco started trying his hand at professional level in 1996, when he signed for Corinthians. He spent a year with the club and in 1997 moved to Benfica. But hardly had Anderson entered the squad, when he was loaned out by Alverca. The 1998/1999 season would find the midfielder serving loan to Salgueiros. In total, he made 12 appearances for the temporary team"', 'Portugal, Freestyle, Deco', 5, '2020-11-28'),
(13, 'Ronaldo', 'Ronaldo established himself as an unstoppable scorer for European soccer teams in the mid-1990s. He bounced back from a disappointing finish to the 1998 World Cup and a series of knee injuries to lead Brazil to victory in the 2002 World Cup, and retired in 2011 as one of the games all-time greats."', 'Brazil, Freestyle, Ronaldo', 5, '2020-11-28');


-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `reaction` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blog_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `comment`, `date`, `reaction`, `user_id`, `blog_id`) VALUES
(2, 'The Best ever', '2020-11-29', 'Positive', 5, 4),
(3, 'He did not born to play soccer.', '2020-11-29', 'Negative', 5, 5),
(4, 'He is the greatest', '2020-11-29', 'Positive', 3, 4),
(5, 'Amazing player', '2020-11-28', 'Positive', 3, 6),
(6, 'Yeah! he is amazing', '2020-11-28', 'Positive', 4, 6),
(7, 'Nice player.', '2020-11-29', 'Positive', 4, 5),
(8, 'Incredible player.', '2020-11-30', 'Positive', 5, 4),
(9, 'Bad player.', '2020-11-30', 'Negative', 3, 4),
(10, 'Terrible player.', '2020-11-27', 'Negative', 3, 5),
(11, 'Fast player.', '2020-11-27', 'Positive', 4, 5),
(12, 'Hate him. So selfish.', '2020-11-28', 'Negative', 7, 4),
(13, 'PENALDO! FOOooo lmao.', '2020-11-29', 'Negative', 8, 5),
(14, 'He could not achieve anything without Messi and Xavi', '2020-11-27', 'Negative', 11, 8),
(15, 'He was only scoring in friendlies lol', '2020-11-28', 'Negative', 9, 11),
(16, 'There is only one real Ronaldo!', '2020-11-27', 'Positive', 11, 13);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`user_id`, `follower_id`) VALUES
(4, 5),
(4, 3),
(4, 6),
(5, 3),
(5, 4),
(5, 6),
(3, 5),
(3, 4),
(3, 6),
(6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(50),
  `lastname` varchar(50),
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `email`, `password`) VALUES
(3, 'max', 'Maksymilian', ' Bogatyrov', 'max@gmail.com', '202cb962ac59075b964b07152d234b70'),
(4, 'byron', 'Bayron', 'Pineda','byron@gmail.com', '202cb962ac59075b964b07152d234b70'),
(5, 'sam', 'Samoel', '', 'sam@gmail.com', '202cb962ac59075b964b07152d234b70'),
(6, 'steven', '', '', 'steven@gmail.com', '202cb962ac59075b964b07152d234b70'),
(7, 'admin', 'firstname', 'lastname', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70'),
(8, 'jane', '', 'Johnson', 'jane@gmail.com', '202cb962ac59075b964b07152d234b70'),
(9, 'ivana', '', '', 'ivana@gmail.com', '202cb962ac59075b964b07152d234b70'),
(10, 'darshi', '', '', 'darshi@gmail.com', '202cb962ac59075b964b07152d234b70'),
(11, 'josue', '', '', 'josue@gmail.com', '202cb962ac59075b964b07152d234b70'),
(12, 'sonia', '', '', 'sonia@gmail.com', '202cb962ac59075b964b07152d234b70');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blog_id` (`blog_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`user_id`,`follower_id`),
  ADD KEY `follower_id` (`follower_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
