<?php
	include "eepl_db.php";
	date_default_timezone_set('Asia/Kolkata');
	$date=Date('Y-m-d',strtotime("-2 days"));
	$data1=array();
	$data2=array();
	$data3=array();
	$data4=array();
	$data5=array();
	$data6=array();

    $sql="select Voltage_BR,Voltage_RY,Voltage_YB,Current_R,Current_Y,Current_B,Frequncy,Active_Import,Active_Export,PF,ACTIVE_POWER,Time from modbuskemwell1 where DATE(Time)= '$date' ";
    $res=mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
	
		$slot=0;
		$BLOCK=0;
		$READ=1;
		$ActualExp=0;
		$Frequency=0;
		$INI=0;
		$NN=1;
		$INITIME=0;
		$INIDAY=0;
		$initime=0;
		$finaltime=0;
		$timediff=0;
		$lasttime=0;
			$lastmin=0;
			$lastsec=0;
			$lasthour=0;
			$lastimport=0;
			$importactualfinal=0;
			$importactualfinal15mins=0;
			$importactual=0;
        while($row1=mysqli_fetch_assoc($res))
          	{
        	$row=$row1;
			$slot+=1;
			if( $READ==1)
			{
			$INI=$row['Active_Import'];
			$INIDAY=$row['Active_Import'];
			}
			$ActualExp+=$row['Active_Import'];
			$Frequency+=$row['Frequncy'];
		//	$Frequency+=$row['block'];
			//$UDIFF+=$row['UNITSDIFF'];
			//$UIPEN+= $row['UIPEN'];
			//$UIEXC+= $row['UIEXC'];
			$UNITS=$row['Active_Import']-$INI;
			$UNITSDAY=$row['Active_Import']-$INIDAY;
			$UNITSDAY= number_format($UNITSDAY, 0);
			$UNITS= number_format($UNITS, 0);
			$FEQLAST=$row['Frequncy'];
			$READ++;
			
			$initime=date_create_from_format("Y-m-d H:i:s",$row['Time']);
			$inimin=(date_format($initime,"i"));
			$inisec=(date_format($initime,"s"));
			$inihour=(date_format($initime,"H"));
			$diffhour=$inihour-$lasthour;
			$diffmin=$inimin-$lastmin;
			$diffsec=$inisec-$lastsec;
			$timediff= (($diffhour*60*60)+($diffmin*60)+($diffsec))/(3600);
			
			//$finaltime=date_create_from_format("Y-m-d H:i:s",$row['Time']);
			//$timediff=$finaltime-$initime;
			$Demand1= (( ($row['Voltage_BR']+$row['Voltage_RY']+$row['Voltage_YB'])/(3)) *  (       ($row['Current_R']+$row['Current_Y']+$row['Current_B'])/(3)) *1.732)/1000;
			
					if ($row['PF']>=0)
					$ACTIVEKWH= $Demand1 *$row['PF'];
					      Elseif  ($row['PF'] <0)
					      $ACTIVEKWH=(-1)* $Demand1 *$row['PF'];
					
			
			
			
			$importactual=$ACTIVEKWH*$timediff;
			$importactualfinal=$importactual+$lastimport;
			$importactualfinal15mins=$importactualfinal-$lastimport;
			$importactualfinal = number_format($importactualfinal, 0);
			$importactualfinal15mins = number_format($importactualfinal15mins, 0);
			
			$avgkw=($importactualfinal15mins*4)/1000;
			$avgkw = number_format($avgkw, 2);
			$date=date_create_from_format("Y-m-d H:i:s",$row['Time']);
			//echo date_format($date,"i");
			if ($FEQLAST>0)
			{
				//$SCHLAST=$row['Schedule'];
			}
		
		
          if ($NN==1)
           {
           $INITIME=date_format($date,"i");
          // $INITIME=$INITIME+1;
           }
           if((date_format($date,"i")==0) or (date_format($date,"i")==15) or (date_format($date,"i")==30) or (date_format($date,"i")==45))
           {$INITIME=date_format($date,"i");
           }
           elseif((date_format($date,"i")==1) or (date_format($date,"i")==16)  or (date_format($date,"i")==31) or (date_format($date,"i")==46))
           {
           $INITIME=date_format($date,"i");
           // $INITIME=$INITIME+1;
           }
           elseif((date_format($date,"i")==2) or (date_format($date,"i")==17)  or (date_format($date,"i")==32) or (date_format($date,"i")==47))
           {
           $INITIME=date_format($date,"i");
           // $INITIME=$INITIME+1;
           }
           elseif((date_format($date,"i")==3) or (date_format($date,"i")==18)  or (date_format($date,"i")==33) or (date_format($date,"i")==48))
           {
           $INITIME=date_format($date,"i");
           // $INITIME=$INITIME+1;
           }
           elseif((date_format($date,"i")==4) or (date_format($date,"i")==19)  or (date_format($date,"i")==34) or (date_format($date,"i")==49))
           {
           $INITIME=date_format($date,"i");
           // $INITIME=$INITIME+1;
           }
           elseif((date_format($date,"i")==5) or (date_format($date,"i")==20)  or (date_format($date,"i")==35) or (date_format($date,"i")==50))
           {
           $INITIME=date_format($date,"i");
           // $INITIME=$INITIME+1;
           }
            if ( (date_format($date,"i") != $INITIME))
           //if ($NN>12)
            {
	$NN=1;
	
	}
	    
           if ((((date_format($date,"i")==0 )or (date_format($date,"i")==1 )or (date_format($date,"i")==2 )or (date_format($date,"i")==3 )or (date_format($date,"i")==4 )or (date_format($date,"i")==5 ) )and $NN==1) or (((date_format($date,"i")==15 ) or (date_format($date,"i") == 16 )or(date_format($date,"i")==17 )or (date_format($date,"i")==18 )or (date_format($date,"i")==19 ) or (date_format($date,"i")==20 ) )and $NN==1) or (((date_format($date,"i")==30 )or (date_format($date,"i")==31) or (date_format($date,"i")==32 )or (date_format($date,"i")==33 )or (date_format($date,"i")==34 )or (date_format($date,"i")==35 ) )and $NN==1) or (((date_format($date,"i")==45 )or (date_format($date,"i")==46 )or(date_format($date,"i")==47 ) or (date_format($date,"i")==48 )or (date_format($date,"i")==49 )or (date_format($date,"i")==50 ) )and $NN==1) )
           {
          if ($BLOCK>0)
          {
           
           $data1[]=$row['Time'];
           $data2[]=$BLOCK;
           $data3[]=$avgkw;
           $data4[]=$UNITSDAY;
           $data5[]=$UNITS;
          // $data[]="\n";
        
		 
	}
	$NN++;
	$INI=$row['Active_Import'];
	$BLOCK++;
	 $lasttime=date_create_from_format("Y-m-d H:i:s",$row['Time']);
			$lastmin=(date_format($lasttime,"i"));
			$lastsec=(date_format($lasttime,"s"));
			$lasthour=(date_format($lasttime,"H"));
                        $lastimport=$importactual+$lastimport;	
	
	}
	
	
	 	}
                            
           $data1[]=$row['Time'];
           $data2[]=$BLOCK;
           $data3[]=$avgkw;
           $data4[]=$UNITSDAY;
           $data5[]=$UNITS;
        
        
    
	$formatedArr = array();
	$count = 0;
	foreach($data1 as $key => $value){
		$formatedArr[$count][] = $data1[$count];
		$formatedArr[$count][] = $data2[$count];
		$formatedArr[$count][] = $data3[$count];
		$formatedArr[$count][] = $data4[$count];
		$formatedArr[$count][] = $data5[$count];
		$count++;
	}

    //print_r($formatedArr);
	
	$results=array(
		'Time' =>$data1,
		'Block' =>$data2,
		'Average  MW' => $data3,
		'Days Units(meter)- Kwh' =>$data4,
		'BLOCKWISE UNITS (meter)- Kwh' =>$data5
	);
    
	$output=fopen("result.csv","w");
	$headers=array_keys($results);
	
	fputcsv($output, $headers);
   // echo '<pre>';print_r($results);die;
    foreach ($formatedArr as $key=>$value) {
         //echo '<pre>'; print_r($value);die;
    	fputcsv($output, (array)$value);
    }
    fclose($output);

	?>

	 <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script type="text/javascript">
	
		 $(document).ready(function(){	

	       $.ajax({
	            type: 'POST',
	            url: "./EmailService/contact_us_mail.php",
	            success:function(response){
	            	console.log(response);
	            }
	        });

	    });
	</script>