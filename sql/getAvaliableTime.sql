--------------------------------------------------------------
-- Function to calculate avaliable time by day ---------------
--------------------------------------------------------------

CREATE OR REPLACE FUNCTION getAvaliableTime(r_date date)
  RETURNS interval AS
$BODY$DECLARE
	total_time interval;
	user_day_time interval;
	r record;
BEGIN        
	total_time := '00:00:00'::interval;
	for r in 
		select * from users where id != 1 -- it ignore Administrator 
		loop

		select w.day_active_hour into user_day_time from user_work_day w where w.user_id = r.id and w."date" = r_date;
		if (user_day_time is null ) then
			total_time := total_time + r.day_active_hour;
		else 
			total_time := total_time + user_day_time;
		end if ;
	end loop;

	return total_time;
END $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
COMMENT ON FUNCTION getAvaliableTime(date) IS 'Função para carregar a quantidade de horas disponíveis no dia';

 -- select getAvaliableTime('2014-03-22')


--DROP FUNCTION getavaliabletime(date,integer) 
CREATE OR REPLACE FUNCTION getAvaliableTime(r_date date, userid integer)
  RETURNS interval AS
$BODY$DECLARE
	user_day_time interval;
	r record;
BEGIN

	select w.day_active_hour 
	  into user_day_time 
	  from user_work_day w 
	 where w.user_id = userid 
	   and w."date" = r_date;
	
	if (user_day_time is null ) then
		select day_active_hour 
		  into user_day_time 
		  from users 
		 where id = userid;
		 
	end if;
	
	return user_day_time;
END $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
COMMENT ON FUNCTION getAvaliableTime(date, integer) IS 'Função para carregar a quantidade de horas disponíveis no dia por usuário';
 -- select getAvaliableTime('2014-03-21', 2)