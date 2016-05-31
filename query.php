<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	
	//echo "BBB";
	
	function startQuery($Repository, $Query, $Dic, $Head) {
		$url = "http://localhost:8080/openrdf-workbench/repositories/". $Repository."/query?action=exec&queryLn=SPARQL&query=".urlencode($Query)."&Accept=application/sparql-results%2Bjson";
		//echo $url;
		$content = file_get_contents($url);
		$results = json_decode($content, true);
		//return $results;
		
		for($i =0 ; $i<sizeof($results["results"]["bindings"]); $i++){
			$tempArray = $results["results"]["bindings"][$i];
		
			for($j =0 ; $j<sizeof($Head); $j++){
				if(array_key_exists($Head[$j], $tempArray)){
					array_push($Dic[$Head[$j]], $tempArray[$Head[$j]]["value"]);
				}
				else{
					array_push($Dic[$Head[$j]], "");
				}
			}
		}
		//var_dump($Dic);
		
		return $Dic;
		
	}
	
	if(!empty($_GET))
	{	
		
		$type = $_GET["type"];
		$option = $_GET["option"];
		$param1=trim($_GET["param1"]);
		
		$param2="";
		if(($type=="1" and $option=="2")or($type=="3" and $option=="3")){
			//echo "param2!!!";
			//echo $_GET["param2"];
			$param2 =trim($_GET["param2"]);
				
		}
		
		//echo $type."<br>".$option."<br>".$param1."<br>".$param2;
       		
       		$repository = "";
       		$query = "";
       	       	
       	       	if($type=="1"){
       	       		$repository ="faculty";
       	       		if($option=="1"){
       	       			
       	       			$query="PREFIX about: <http://example.org/data/CS_faculty_final.csv#>
PREFIX about1: <http://example.org/data/ee_faculty_final.csv#>
PREFIX about2: <http://example.org/data/INF_faculty_final.csv#>

SELECT DISTINCT ?Name ?Category ?Position ?Cellphone ?E_mail ?Office ?Research_Area	
WHERE
{
  {?x about:Category ?Category .
  ?x about:Name ?Name .
  ?x about:Position ?Position .
  Optional{?x about:Cellphone ?Cellphone}
  Optional{?x about:E_mail ?E_mail}
  Optional{?x about:Office ?Office}
  Optional{?x about:Research_Area ?Research_Area}
  Optional{?x about:Alias ?Alias}
  FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
UNION{?x about1:Category ?Category .
  ?x about1:Name ?Name .
  ?x about1:Position ?Position .
  Optional{?x about1:Cellphone ?Cellphone}
  Optional{?x about1:E_mail ?E_mail}
  Optional{?x about1:Office ?Office}
  Optional{?x about1:Research_Area ?Research_Area}
  Optional{?x about1:Alias ?Alias}
  FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
UNION{?x about2:Category ?Category .
  ?x about2:Name ?Name .
  ?x about2:Position ?Position .
  Optional{?x about2:Cellphone ?Cellphone}
  Optional{?x about2:E_mail ?E_mail}
  Optional{?x about2:Office ?Office}
  Optional{?x about2:Research_Area ?Research_Area}
  Optional{?x about2:Alias ?Alias}
  FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
}ORDER BY(?Name)";
						
				$head = array("Name", "Category", "Position", "Cellphone", "E_mail", "Office", "Research_Area");
				$dic = array( "Name"=>array(), 
						"Category"=>array(), 
						"Position"=>array(), 
						"Cellphone"=>array(), 
						"E_mail"=>array(), 
						"Office"=>array(), 
						"Research_Area"=>array()		);
		   				
		   		$queryResult=startQuery($repository, $query, $dic, $head);
				
				echo json_encode($queryResult);
				
       	       		}
       	       		else{
       	       			//if($option=="2")
       	       			$prefix_Array = array("cs"=>"PREFIX about: <http://example.org/data/CS_faculty_final.csv#>", 
		   					    "ee" => "PREFIX about: <http://example.org/data/ee_faculty_final.csv#>",
		   					    "inf"=> "PREFIX about: <http://example.org/data/INF_faculty_final.csv#>");
		   			
		   		//echo $param2;		    
       	       			$filter="";
       	       			
       	       			if($param1=="cs"){
       	       				if($param2=="fulltime"){
       	       					$filter="FILTER (regex(?Category,'^research faculty$','i')||regex(?Category,'^tenure track faculty$','i')||regex(?Category,'^teaching faculty$','i'))";
       	       				}
       	       				elseif($param2=="parttime"){
       	       					$filter="FILTER (regex(?Category,'^joint faculty$','i')||regex(?Category,'^adjunct part time$','i'))";
       	       				}
       	       				elseif($param2=="tenure"){
       	       					$filter="FILTER (regex(?Category,'^tenured and tenure track faculty$','i'))";
       	       				}
       	       				elseif($param2=="research"){
       	       					$filter="FILTER (regex(?Category,'^research faculty$','i'))";
       	       				}
       	       				elseif($param2=="teaching"){
       	       					$filter="FILTER (regex(?Category,'^teaching faculty$','i'))";
       	       				}
       	       				elseif($param2=="joint"){
       	       					$filter="FILTER (regex(?Category,'^joint faculty$','i'))";
       	       				}
       	       				elseif($param2=="adjunct"){
       	       					$filter="FILTER (regex(?Category,'^adjunct part time$','i'))";
       	       				}
       	       			}
       	       			elseif($param1=="ee"){
       	       				if($param2=="fulltime"){
       	       					$filter="FILTER (regex(?Category,'^Full-time Faculty$','i'))";
       	       				}
       	       				elseif($param2=="parttime"){
       	       					$filter="FILTER (regex(?Category,'^adjunct faculty$','i'))";
       	       				}
       	       				elseif($param2=="research"){
       	       					$filter="FILTER (regex(?Category,'^Research Faculty$','i'))";
       	       				}
       	       				elseif($param2=="adjunct"){
       	       					$filter="FILTER (regex(?Category,'^Adjunct Faculty$','i'))";
       	       				}
       	       				elseif($param2=="lecturer"){
       	       					$filter="FILTER (regex(?Category,'^Part-Time Lecturers$','i'))";
       	       				}
       	       			}
       	       			else{
       	       				//if($param1=="inf")
       	       				if($param2=="fulltime"){
       	       					$filter="FILTER (regex(?Category,'^Director$','i')||regex(?Category,'^Informatics Faculty$','i')||regex(?Category,'^faculty Board of Advisors$','i'))";
       	       				}
       	       				elseif($param2=="parttime"){
       	       					$filter="FILTER (regex(?Category,'^Part Time Lecturer$','i'))";
       	       				}
       	       				elseif($param2=="director"){
       	       					$filter="FILTER (regex(?Category,'^Director$','i'))";
       	       				}
       	       				elseif($param2=="inf_faculty"){
       	       					$filter="FILTER (regex(?Category,'^Informatics Faculty$','i'))";
       	       				}
       	       				elseif($param2=="advisor"){
       	       					$filter="FILTER (regex(?Category,'^faculty Board of Advisors$','i'))";
       	       				}
					elseif($param2=="p_lecturer"){
       	       					$filter="FILTER (regex(?Category,'^Part Time Lecturer$','i'))";
       	       				}
       	       			}
       	       			
       	       			$query= $prefix_Array[$param1] . "
SELECT DISTINCT ?Category ?Name ?Position ?Cellphone ?E_mail ?Office ?Research_Area	
WHERE
{
  ?x about:Category ?Category .
  ?x about:Name ?Name .
  Optional{?x about:Position ?Position}
  Optional{?x about:Cellphone ?Cellphone}
  Optional{?x about:E_mail ?E_mail}
  Optional{?x about:Office ?Office}
  Optional{?x about:Research_Area ?Research_Area}"."
  ".$filter."
}ORDER BY ASC(?Category) ASC(?Name)";
				//echo $query;	 
				
       				$head = array("Category", "Name", "Position", "Cellphone", "E_mail", "Office", "Research_Area");
				$dic = array("Category"=>array(),  
						"Name"=>array(), 
						"Position"=>array(), 
						"Cellphone"=>array(), 
						"E_mail"=>array(), 
						"Office"=>array(), 
						"Research_Area"=>array()		);
		   				
		   		$queryResult=startQuery($repository, $query, $dic, $head);
				
				echo json_encode($queryResult);	       			
       	       			
       	       		}
       	       	}	
       	       	elseif($type=="2"){
       			//echo "2";
       			if($option=="1"){
       				//echo "21";
       				$repository = "CS_phd_student_faculty";
       				
       				$query="PREFIX about: <http://example.org/data/CS_phd_student_faculty.csv#>
SELECT Distinct ?Name ?Email ?FacultyAdvisor
WHERE
{
  ?x about:Name ?Name.
  ?x about:Email ?Email.
  ?x about:FacultyAdvisor ?FacultyAdvisor.
  FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i'))
}ORDER BY ASC(?Name)";
				
				$head = array("Name", "Email", "FacultyAdvisor");
				$dic = array( "Name"=>array(), 
						"Email"=>array(), 
						"FacultyAdvisor"=>array()		);
		   				
		   		$queryResult=startQuery($repository, $query, $dic, $head);
				
				echo json_encode($queryResult);
       			}
       			elseif($option=="2"){
       				
       				$repository="faculty";
				$query="PREFIX about: <http://example.org/data/CS_faculty_final.csv#>

SELECT Distinct ?Name
WHERE
{ ?x about:Name ?Name .
  Optional{?x about:Alias ?Alias} 
  FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))
}";
				//echo $query;
	
				$head = array("Name");
				
				$dic = array("Name"=>array()	);
						
				$facultyResult=startQuery($repository, $query, $dic, $head);
				//var_dump($facultyResult);
	
				$searchedfaculty = $facultyResult['Name'];
				//echo json_encode($searchedfaculty);
				$filter="";
				if(sizeof($searchedfaculty)==0){
					$filter="FILTER (regex(?FacultyAdvisor,'^".$param1."','i')||regex(?FacultyAdvisor,' ".$param1."','i'))";

				}
				else{
					for($i=0; $i<sizeof($searchedfaculty);$i++){
						if($i==0){
							$filter="FILTER (regex(?FacultyAdvisor,'^".$searchedfaculty[$i]."$','i')";
						}
						else{
							$filter.="||regex(?FacultyAdvisor,'^".$searchedfaculty[$i]."$','i')";
						}
					}
					$filter.="||regex(?FacultyAdvisor,'^".$param1."','i')||regex(?FacultyAdvisor,' ".$param1."','i'))";
				}				

				$repository = "CS_phd_student_faculty";
       				$query="PREFIX about: <http://example.org/data/CS_phd_student_faculty.csv#>
SELECT Distinct ?FacultyAdvisor ?Name
WHERE
{
  ?x about:FacultyAdvisor ?FacultyAdvisor.
  ?x about:Name ?Name.
  ".$filter."
}ORDER BY ASC(?FacultyAdvisor) ASC(?Name)";
				//echo $query;
				$head = array("FacultyAdvisor", "Name");
				$dic = array("FacultyAdvisor"=>array(),
						"Name"=>array() 		);
		   				
		   		$queryResult1=startQuery($repository, $query, $dic, $head);
		   		
		   		$query="PREFIX about: <http://example.org/data/CS_phd_student_faculty.csv#>
SELECT Distinct (count(distinct ?Name)as ?Total)
WHERE
{
  ?x about:FacultyAdvisor ?FacultyAdvisor.
  ?x about:Name ?Name.
  ".$filter."
}";
				
				$head = array("Total");
				$dic = array("Total"=>array());
		   				
		   		$queryResult2=startQuery($repository, $query, $dic, $head);
		   		
		   		echo json_encode(array_merge($queryResult1, $queryResult2));
       			}
		}
		elseif($type=="3"){
			
			if($option=="1"){
				$repository = "course";
				
				$prefix_Array = array("cs"=>"PREFIX about: <http://example.org/data/CS_course_final.csv#>", 
		   					    "ee" => "PREFIX about: <http://example.org/data/EE_course_final.csv#>",
		   					    "inf"=> "PREFIX about: <http://example.org/data/INF_course_final.csv#>");
		   		$department = preg_split('/\s+/', $param1)[0];
		   		
		   		$prefix = "";
		   		if($department == "CSCI" or $department =="csci"){
		   			$prefix=$prefix_Array['cs'];
		   		}
		   		elseif($department == "EE" or $department == "ee"){
		   			$prefix=$prefix_Array['ee'];
		   		}
		   		elseif($department == "INF" or $department == "inf"){
		   			$prefix=$prefix_Array['inf'];
		   		}
		   		
				if($prefix){
					$query=$prefix."
SELECT DISTINCT ?CourseName ?Unit ?CourseID ?Instructor ?Prerequisite ?Corequisite
WHERE
{ 
  ?x about:CourseID ?CourseID .
  ?x about:CourseName ?CourseName .
  ?x about:Instructor ?Instructor .
  ?x about:Unit ?Unit .
  Optional{?x about:Prerequisite ?Prerequisite}
  Optional{?x about:Corequisite ?Corequisite}
  Filter regex(?CourseID, '^".$param1."$', 'i')
}";
				
					//echo $query;
					$head = array("CourseName", "Unit", "CourseID", "Instructor", "Prerequisite", "Corequisite");
				
					$dic = array("CourseName"=>array(),
							"Unit"=>array(),
							"CourseID"=>array(),
							"Instructor"=>array(),
							"Prerequisite"=>array(), 
							"Corequisite"=>array()	);	
		   			
		   			$queryResult=startQuery($repository, $query, $dic, $head);
		   			echo json_encode($queryResult);
		   		}
		   		else{
		   			$queryResult = array("CourseName"=>array(),
								    "Unit"=>array(),
								    "CourseID"=>array(),
								    "Instructor"=>array(),
								    "Prerequisite"=>array(), 
								    "Corequisite"=>array()	);
					echo json_encode($queryResult);
		   		}
		   		
			}
			elseif($option=="2"){
				$repository="faculty";
				$query="PREFIX about1: <http://example.org/data/CS_faculty_final.csv#>
PREFIX about2: <http://example.org/data/ee_faculty_final.csv#>
PREFIX about3: <http://example.org/data/INF_faculty_final.csv#>

SELECT Distinct ?Name
WHERE
{ { ?x about1:Name ?Name .
    Optional{?x about1:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
  Union{
    ?x about2:Name ?Name .
    Optional{?x about2:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
  Union{
    ?x about3:Name ?Name .
    Optional{?x about3:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
}";
	
				$head = array("Name");
				
				$dic = array("Name"=>array()	);
						
				$facultyResult=startQuery($repository, $query, $dic, $head);
				//var_dump($facultyResult);
	
				$searchedfaculty = $facultyResult['Name'];
				
				$filter="";
				if(sizeof($searchedfaculty)==0){
					$filter="FILTER (regex(?Instructor,'^".$param1."','i')||regex(?Instructor,' ".$param1."','i'))";

				}
				else{
					for($i=0; $i<sizeof($searchedfaculty);$i++){
						if($i==0){
							$filter="FILTER (regex(?Instructor,'^".$searchedfaculty[$i]."$','i')";
						}
						else{
							$filter.="||regex(?Instructor,'^".$searchedfaculty[$i]."$','i')";
						}
					}
					
					$filter.="||regex(?Instructor,'^".$param1."','i')||regex(?Instructor,' ".$param1."','i'))";
				}			
					
				$repository = "course";
				
				$query="PREFIX about: <http://example.org/data/CS_course_final.csv#>
PREFIX about1: <http://example.org/data/EE_course_final.csv#>
PREFIX about2: <http://example.org/data/INF_course_final.csv#>
SELECT DISTINCT ?Instructor ?CourseName ?Unit ?CourseID ?Prerequisite ?Corequisite
WHERE
{ 
  {?x about:CourseID ?CourseID .
  ?x about:CourseName ?CourseName .
  ?x about:Instructor ?Instructor .
  ?x about:Unit ?Unit .
  Optional{?x about:Prerequisite ?Prerequisite}
  Optional{?x about:Corequisite ?Corequisite}
    ".$filter."}
  UNION{?x about1:CourseID ?CourseID .
  ?x about1:CourseName ?CourseName .
  ?x about1:Instructor ?Instructor .
  ?x about1:Unit ?Unit .
  Optional{?x about1:Prerequisite ?Prerequisite}
  Optional{?x about1:Corequisite ?Corequisite}
    ".$filter."}
  UNION{?x about2:CourseID ?CourseID .
  ?x about2:CourseName ?CourseName .
  ?x about2:Instructor ?Instructor .
  ?x about2:Unit ?Unit .
  Optional{?x about2:Prerequisite ?Prerequisite}
  Optional{?x about2:Corequisite ?Corequisite}
    ".$filter."}
}ORDER BY ASC(?CourseID) ASC(?Instructor)";

				//echo $query;
				$head = array("Instructor", "CourseName", "Unit", "CourseID", "Prerequisite", "Corequisite");
				
				$dic = array("Instructor"=>array(),
						"CourseName"=>array(),
						"Unit"=>array(),
						"CourseID"=>array(),
						"Prerequisite"=>array(), 
						"Corequisite"=>array()	);
		   			
		   		$queryResult=startQuery($repository, $query, $dic, $head);
		  		echo json_encode($queryResult);		   		
		   		
		   		//echo "KKK";
		   		
			}
			else{
				//if($option=="3")
		   		$repository = "course";
		   		
		   		$prefix_Array = array("cs"=>"PREFIX about: <http://example.org/data/CS_course_final.csv#>", 
		   					    "ee" => "PREFIX about: <http://example.org/data/EE_course_final.csv#>",
		   					    "inf"=> "PREFIX about: <http://example.org/data/INF_course_final.csv#>");
		   		$courseID_Array=array("cs"=>"CSCI", "ee"=>"EE", "inf"=>"INF");
		   		
		   		$prefix=$prefix_Array[$param1];
		   		$courseID=$courseID_Array[$param1];
		   			
		   		$query= $prefix . "
SELECT DISTINCT ?CourseID ?CourseName	
WHERE
{
  ?x about:CourseName ?CourseName .
  ?x about:Unit ?Unit .
  ?x about:CourseID ?CourseID.
  ?x about:Unit '".$param2."' .
  filter regex(?CourseID, '".$courseID."')
}ORDER BY ASC(?CourseID)";
					//echo $query;
				$head = array("CourseID", "CourseName");
				
				$dic = array("CourseID"=>array(),
						"CourseName"=>array()	);
		   			
		   		$queryResult=startQuery($repository, $query, $dic, $head);
		 		echo json_encode($queryResult);
				
			}
		}   
		else{
			
			//if($type=="4")
			if($option=="1"){
				//search the possible faculty and get their index
				$repository="faculty";
				$query="PREFIX about1: <http://example.org/data/CS_faculty_final.csv#>
PREFIX about2: <http://example.org/data/ee_faculty_final.csv#>
PREFIX about3: <http://example.org/data/INF_faculty_final.csv#>

SELECT Distinct ?Name
WHERE
{ { ?x about1:Name ?Name .
    Optional{?x about1:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
  Union{
    ?x about2:Name ?Name .
    Optional{?x about2:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
  Union{
  	?x about3:Name ?Name .
    Optional{?x about3:Alias ?Alias} 
    FILTER (regex(?Name,'^".$param1."','i')||regex(?Name,' ".$param1."','i')||regex(?Alias,'^".$param1."','i')||regex(?Alias,' ".$param1."','i'))}
}ORDER BY ASC(?Name)";
	
				$head = array("Name");
				
				$dic = array("Name"=>array()	);
						
				$facultyResult=startQuery($repository, $query, $dic, $head);
				//var_dump($facultyResult);
				
				$searchedfaculty = $facultyResult['Name'];
				if(sizeof($searchedfaculty)>0){
					for($i=0; $i<sizeof($searchedfaculty);$i++){
						if($i==0){
							$filter="FILTER (regex(?index,'^".$searchedfaculty[$i]."$','i')";
						}
						else{
							$filter.="||regex(?index,'^".$searchedfaculty[$i]."$','i')";
						}
					}
					$filter.=")";
					//echo $filter;
		
					$repository = "publication";
 					$query="PREFIX about: <http://example.org/data/11.csv#>

SELECT Distinct ?index ?title ?category ?author ?year ?url 
WHERE
{
  ?x about:index ?index .
  ?x about:title ?title .
  ?x about:category ?category .
  ?x about:author ?author .
  ?x about:year ?year .
  ?x about:url ?url .
  ?x about:index ?index.
  ".$filter."
}ORDER BY ASC(?index) DESC(?year)";
		
					$head = array("index", "author", "category", "title", "year", "url");
				
					$dic = array("index"=>array(),
							"author"=>array(), 
							"category"=>array(), 
							"title"=>array(), 
							"year"=>array(), 
							"url"=>array()	);
						
					$queryResult=startQuery($repository, $query, $dic, $head);
					
					for($i=0; $i<sizeof($queryResult['author']); $i++){
		   				$queryResult['author'][$i] = str_replace("\n",", ",$queryResult['author'][$i]);
		   			}
					//var_dump($queryResult);
		
					/*****<Summary>*****/
					//categoryCount
					$sumFilter1 = str_replace("?index","?sumIndex1",$filter);
					//echo $sumFilter;
					$query="PREFIX about: <http://example.org/data/11.csv#>

SELECT DISTINCT ?sumIndex1 ?sumCategory (count (Distinct ?title) as ?sumCategoryTotal)
WHERE
{
  ?x about:index ?sumIndex1 .
  ?x about:title ?title .
  ?x about:category ?sumCategory.
  ".$sumFilter1."
}
group by ?sumIndex1 ?sumCategory
order by ASC(?sumIndex1) ASC(?sumCategory)";

					//echo $query;
					//echo $query;
					$head = array("sumIndex1", "sumCategory", "sumCategoryTotal");
				
					$dic = array("sumIndex1"=>array(),
							"sumCategory"=>array(), 
							"sumCategoryTotal"=>array()	);
					$sumCategoryCount=startQuery($repository, $query, $dic, $head);
					//var_dump($sumCategoryCount);
		
					//total publications after 2010
					$sumFilter2 = str_replace("?index","?sumIndex2",$filter);
					
					$query="PREFIX about: <http://example.org/data/11.csv#>

SELECT DISTINCT ?sumIndex2 (count(distinct ?title)as ?sum2010totalPub)
WHERE
{
  ?x about:index ?sumIndex2 .
  ?x about:title ?title .
  ?x about:year ?year .
  FILTER(xsd:integer(?year) >= 2010) 
  ".$sumFilter2."
}
group by ?sumIndex2
order by ASC(?sumIndex2)";
					$head = array("sumIndex2", "sum2010totalPub");
				
					$dic = array("sumIndex2"=>array(),
							"sum2010totalPub"=>array());
							
					$sum2010totalPub=startQuery($repository, $query, $dic, $head);
					
					for($i=0; $i<sizeof($sum2010totalPub['sum2010totalPub']); $i++){
						if($sum2010totalPub['sum2010totalPub'][$i]==0 and $sum2010totalPub['sumIndex2'][$i]==""){
							$sum2010totalPub['sumIndex2'][$i]=$searchedfaculty[$i];
						}
					}
					//var_dump($sum2010totalPub);
		
					//total publications
					
					$sumFilter3 = str_replace("?index","?sumIndex3",$filter);
					
					$query="PREFIX about: <http://example.org/data/11.csv#>

SELECT DISTINCT ?sumIndex3 (count(distinct ?title)as ?sumTotalPub)
WHERE
{
  ?x about:index ?sumIndex3 .
  ?x about:title ?title .
  ?x about:year ?year .
  ".$sumFilter3."
}
group by ?sumIndex3
order by ASC(?sumIndex3)";

					$head = array("sumIndex3", "sumTotalPub");
				
					$dic = array("sumIndex3"=>array(),
							"sumTotalPub"=>array());		
					$sumTotalPub=startQuery($repository, $query, $dic, $head);
					
					for($i=0; $i<sizeof($sumtotalPub['sumtotalPub']); $i++){
						if($sum2010totalPub['sum2010totalPub'][$i]==0 and $sumtotalPub['sumIndex3'][$i]==""){
							$sumtotalPub['sumIndex3'][$i]=$searchedfaculty[$i];
						}
					}
					
					//var_dump($sumTotalPub);
					
					$finalResult=array_merge($queryResult, $sumCategoryCount, $sum2010totalPub, $sumTotalPub);
					echo json_encode($finalResult);
					
				}
				else{
					$queryResult = array("index"=>array(),
					   			   "author"=>array(), 
					   			   "category"=>array(), 
					    			   "title"=>array(), 
					    			   "year"=>array(), 
					    			   "url"=>array()	);
						
					$sumCategoryCount = array("sumIndex1"=>array(),
						    			    "sumCategory"=>array(), 
						   			    "sumCategoryTotal"=>array()	);
						    
					$sum2010totalPub = array("sumIndex2"=>array(),
						   			   "sum2010totalPub"=>array());
						   
					$sumTotalPub = array("sumIndex3"=>array(),
					    			     "sumTotalPub"=>array());
		
					$finalResult=array_merge($queryResult, $sumCategoryCount, $sum2010totalPub, $sumTotalPub);
					
					//echo($finalResult);
					echo json_encode($finalResult);
					
				}
				
			}
			else{
				//echo $param1;
				//if($option=="2")
				$repository = "publication";
				$query= "PREFIX about: <http://example.org/data/11.csv#>
SELECT DISTINCT ?title ?category ?author ?year ?url 	
WHERE
{
  ?x about:title ?title .
  filter regex(?title, '".$param1."', 'i')
  ?x about:category ?category.
  ?x about:author ?author .
  ?x about:year ?year .
  ?x about:url ?url .
}ORDER BY ASC(?index) DESC(?year)";

				//echo $query;
				$head = array("category", "author", "title", "year", "url");
				
				$dic = array("category"=>array(), 
						"author"=>array(), 
						"title"=>array(), 
						"year"=>array(), 
						"url"=>array()	);
		   			
		   		$queryResult=startQuery($repository, $query, $dic, $head);
		   		
		   		for($i=0; $i<sizeof($queryResult['author']); $i++){
		   			$queryResult['author'][$i] = str_replace("\n",", ",$queryResult['author'][$i]);
		   		}
		   		
		   		//echo($queryResult);
		   		echo json_encode($queryResult);
			}
			
		}   	       	
		
       	 }

?>