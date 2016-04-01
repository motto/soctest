drop trigger if exists trig_verzment;
delimiter $$
create trigger trig_verzment before update on test for each row
                                                               begin
if OLD.var5 != NEW.var5 then
                insert into ment(tabla,tablaid, regiadat, ujadat) values('test',NEW.id, OLD.var5, NEW.var5);
        end if;
        if OLD.szoveg != NEW.szoveg then
                insert into txtment(tabla,tablaid, regiadat, ujadat) values('test',NEW.id, OLD.szoveg, NEW.szoveg);
        end if;
end$$
delimiter ;