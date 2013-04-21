-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2004 Brian Bruns
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

DROP TABLE IF EXISTS DataQualityCodes;
CREATE TABLE DataQualityCodes
 (
	ID			Int,
	QualityCode			Varchar (510),
	QualityCodeLong			Varchar (510),
	Description			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS GDB_DatabaseLocks;
CREATE TABLE GDB_DatabaseLocks
 (
	LockID			Int,
	LockType			Int,
	UserName			Text (255),
	MachineName			Text (255)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Glossary;
CREATE TABLE Glossary
 (
	ID			Int,
	Term			Varchar (510),
	Definition			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Hurricane;
CREATE TABLE Hurricane
 (
	HID			Int,
	HName			Varchar (510),
	HYear			Double,
	HNameYYYY			Varchar (510),
	Serial_Num			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS HurricaneLandfall;
CREATE TABLE HurricaneLandfall
 (
	HLFID			Int,
	HID			Int,
	SerialNum			Varchar (510),
	LFDate_Time			Date ,
	LFState			Varchar (510),
	LFCity			Varchar (510),
	LFWind			Int,
	MaxWind			Int,
	LFCat			Varchar (510),
	LFPres			Double,
	MinPres			Double,
	MaxPres			Double,
	LFDiam			Double,
	MinDiam			Double,
	MaxDiam			Double,
	TrkAngle			Varchar (510),
	TrkStraight			Varchar (510),
	NrShrSlope			Varchar (510),
	RefID			Int,
	LFSpeed			Int,
	Latitude			Double,
	Longitude			Double,
	Image			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS HurricanePhotos;
CREATE TABLE HurricanePhotos
 (
	ID			Int,
	HID			Int,
	PName			Varchar (510),
	PLink			Text (255),
	Description			Varchar (510),
	Latitude			Int,
	Longitude			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS HurricaneTrackPoints;
CREATE TABLE HurricaneTrackPoints
 (
	ID			Int,
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	RefID			Int,
	LSFlag			Varchar (4),
	Date_Time			Varchar (510),
	Flag12hrs			Varchar (510),
	Flag72hrs			Varchar (510),
	TrackAngleFlags			Varchar (4),
	Orig_IB_ID			Int,
	LSFlag_LF2			Varchar (510),
	Flag12hrs_LF2			Int,
	Flag72hrs_LF2			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS IbTrACS_Modified_v03r02;
CREATE TABLE IbTrACS_Modified_v03r02
 (
	ID			Int,
	Serial_Num			Varchar (510),
	Season			Double,
	Num			Double,
	Basin			Varchar (510),
	Sub_basin			Varchar (510),
	Name			Varchar (510),
	ISO_time			Date ,
	Nature			Varchar (510),
	Latitude			Double,
	Longitude			Double,
	Wind_WMO			Double,
	Pres_WMO			Double,
	Center			Varchar (510),
	Wind_WMO_Percentile			Double,
	Pres_WMO_Percentile			Double,
	hurdat_atl_lat			Double,
	hurdat_atl_lon			Double,
	hurdat_atl_wind			Double,
	hurdat_atl_pres			Double,
	atcf_rmw			Double,
	atcf_poci			Double,
	atcf_roci			Double,
	atcf_eye			Double,
	atcf_wrad34_rad1			Double,
	atcf_wrad34_rad2			Double,
	atcf_wrad34_rad3			Double,
	atcf_wrad34_rad4			Double,
	atcf_wrad50_rad1			Double,
	atcf_wrad50_rad2			Double,
	atcf_wrad50_rad3			Double,
	atcf_wrad50_rad4			Double,
	atcf_wrad64_rad1			Double,
	atcf_wrad64_rad2			Double,
	atcf_wrad64_rad3			Double,
	atcf_wrad64_rad4			Double,
	HID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpMarkType;
CREATE TABLE lkpMarkType
 (
	MarkType			Varchar (510),
	MarkTypeID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpRefHurricane;
CREATE TABLE lkpRefHurricane
 (
	HID			Int,
	RefID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpWLMeasType;
CREATE TABLE lkpWLMeasType
 (
	WLMeasTypeID			Int,
	WLMeasType			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Radius_MinMax_Diam;
CREATE TABLE Radius_MinMax_Diam
 (
	HID			Int,
	MaxOfatcf_rmw			Double,
	MinOfatcf_rmw			Double,
	MaxDiameter			Int,
	MinDiameter			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpLFPoints_X;
CREATE TABLE lkpLFPoints_X
 (
	ID			Int,
	HID			Int,
	LSFlag			Varchar (4),
	Flag12hrs			Varchar (510),
	Flag72hrs			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrkST_LineDistance;
CREATE TABLE TrkST_LineDistance
 (
	HID			Int,
	Latitude			Double,
	Flag72hrs			Varchar (510),
	Longitude			Double
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrkST_EntireTrack;
CREATE TABLE TrkST_EntireTrack
 (
	ID			Int,
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	Flag72hrs			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrackLength72hrs;
CREATE TABLE TrackLength72hrs
 (
	HID			Varchar (510),
	EntireTKLength			Double,
	ID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS LineLength72hrs;
CREATE TABLE LineLength72hrs
 (
	HID			Varchar (510),
	LineLength			Double,
	ID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrackStraightCalc;
CREATE TABLE TrackStraightCalc
 (
	EntireTKLength			Double,
	LineLength			Double,
	TrackStrght_Indicator			Varchar (510),
	TrackStraightness			Double,
	HID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Averages;
CREATE TABLE Averages
 (
	AvgOfTrackStraightness			Double
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrkSpeed_Length;
CREATE TABLE TrkSpeed_Length
 (
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	Flag12hrs			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TrkSpeed_LengthCalcs;
CREATE TABLE TrkSpeed_LengthCalcs
 (
	Trk12hrLength			Double,
	ID			Int,
	TrackSpeed			Int,
	HID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpHurricaneAnglePoints;
CREATE TABLE lkpHurricaneAnglePoints
 (
	ID			Int,
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	LSFlag			Varchar (4),
	IDJustAfterLF			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS HurricaneApproachAngle_Points;
CREATE TABLE HurricaneApproachAngle_Points
 (
	ID			Int,
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	TrackAngleFlags			Varchar (4)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS HurricaneGeorges;
CREATE TABLE HurricaneGeorges
 (
	WMID			Double,
	HID			Double,
	WLMeasTypeID			Double,
	MarkTypeID			Double,
	MeasElev			Double,
	GndElev			Varchar (510),
	VDatumID			Double,
	Latitude			Double,
	Longitude			Double,
	County			Varchar (510),
	Quality			Varchar (510),
	DQC_ReportedQuality			Varchar (510),
	State			Varchar (510),
	TownNear			Varchar (510),
	Collector			Varchar (510),
	In_Out			Varchar (510),
	DQC_In_Out			Varchar (510),
	HYear			Double,
	DQC_Hyear			Varchar (510),
	RefID			Double,
	QualityCalc			Varchar (510),
	QualityCode			Varchar (510),
	OrigName			Varchar (510),
	ID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Ivan2004;
CREATE TABLE Ivan2004
 (
	HID			Double,
	WLMeasTypeID			Double,
	MarkTypeID			Varchar (510),
	MeasElev			Double,
	GndElev			Varchar (510),
	VDatumID			Double,
	Latitude			Double,
	Longitude			Double,
	County			Varchar (510),
	ReportedQuality			Varchar (510),
	DQC_ReportedQuality			Varchar (510),
	State			Varchar (510),
	TownNear			Varchar (510),
	Collector			Varchar (510),
	In_Out			Varchar (510),
	DQC_In_Out			Varchar (510),
	HYear			Double,
	DQC_Hyear			Varchar (510),
	RefID			Double,
	QualityCalc			Varchar (510),
	QualityCode			Varchar (510),
	OrigName			Varchar (510),
	ID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Source_References;
CREATE TABLE Source_References
 (
	RefID			Int,
	RefTitle			Varchar (510),
	Collector			Varchar (510),
	RefType			Varchar (510),
	RefLink			Text (255),
	Authors			Varchar (510),
	Publisher			Varchar (510),
	Chapter			Varchar (510),
	Editor			Varchar (510),
	InPubTitle			Varchar (510),
	Volume			Varchar (510),
	Number			Varchar (510),
	PMonth			Varchar (510),
	PYear			Varchar (100),
	PubAddress			Varchar (510),
	Pages			Varchar (510),
	ISBN			Varchar (510),
	Notes			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS SelectedObjects;
CREATE TABLE SelectedObjects
 (
	SelectionID			Int,
	ObjectID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS WaterMarkPhotos;
CREATE TABLE WaterMarkPhotos
 (
	ID			Int,
	WMID			Int,
	PName			Varchar (510),
	PLink			Text (255),
	Description		Varchar (510),
	PhotoType		VarChar(510)
	
	
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS WaterMarks;
CREATE TABLE WaterMarks
 (
	WMID			Int,
	HID			Int,
	MeasElev			Double,
	GndElev			Int,
	Latitude			Double,
	Longitude			Double,
	County			Varchar (510),
	ReportedQuality			Varchar (510),
	TownNear			Varchar (510),
	Collector			Varchar (510),
	In_Out			Varchar (510),
	RefID			Int,
	OrigName			Varchar (510),
	WLMeasTypeID			Int,
	MarkTypeID			Int,
	VDatumID			Int,
	HYear			Varchar (510),
	QualityCode			Varchar (510),
	DQC_ReportedQuality			Int,
	DQC_In_Out			Int,
	DQC_HYear			Int,
	QualityCalc			Int,
	State			Varchar (4)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpLFPnts;
CREATE TABLE lkpLFPnts
 (
	HID			Int,
	MinOfID			Int,
	MinusID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Selections;
CREATE TABLE Selections
 (
	SelectionID			Int,
	TargetName			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS IBTrACS_v3r2_ForAnalysis;
CREATE TABLE IBTrACS_v3r2_ForAnalysis
 (
	ID			Int,
	HID			Int,
	Serial_Num			Varchar (510),
	Season			Double,
	Name			Varchar (510),
	ISO_time			Date ,
	Latitude			Double,
	Longitude			Double,
	Wind_WMO			Double,
	Pres_WMO			Double,
	atcf_rmw			Double,
	Num			Double,
	LSFlag			Varchar (510),
	LSFlag_LF2			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Pres_Min;
CREATE TABLE Pres_Min
 (
	HID			Int,
	MinOfPres_WMO			Double
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Rad_Diam_Min;
CREATE TABLE Rad_Diam_Min
 (
	HID			Int,
	MinOfatcf_rmw			Double,
	Diam_Min			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Location_LFFlag;
CREATE TABLE Location_LFFlag
 (
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	LSFlag			Varchar (4)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Rad_Diam_Max;
CREATE TABLE Rad_Diam_Max
 (
	HID			Int,
	MaxOfatcf_rmw			Double,
	LSFlag			Varchar (510),
	Diam_Max			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS All_Features;
CREATE TABLE All_Features
 (
	HID			Int,
	Wind_WMO			Double,
	MaxOfWind_WMO			Double,
	Pres_WMO			Double,
	MinOfPres_WMO			Double,
	MaxOfPres_WMO			Double,
	Diam_LF			Int,
	Diam_Min			Int,
	Diam_Max			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkp_DQC_year_before1990;
CREATE TABLE lkp_DQC_year_before1990
 (
	WMID			Int,
	HYear			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_inside_HWM;
CREATE TABLE DQC_inside_HWM
 (
	WMID			Int,
	In_Out			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_Ouside_HWM;
CREATE TABLE DQC_Ouside_HWM
 (
	WMID			Int,
	In_Out			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_InOut_IsNull;
CREATE TABLE DQC_InOut_IsNull
 (
	WMID			Int,
	In_Out			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_RepQuality_Excellent;
CREATE TABLE DQC_RepQuality_Excellent
 (
	WMID			Int,
	ReportedQuality			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_RepQuality_Good;
CREATE TABLE DQC_RepQuality_Good
 (
	WMID			Int,
	ReportedQuality			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_RepQuality_Fair;
CREATE TABLE DQC_RepQuality_Fair
 (
	WMID			Int,
	ReportedQuality			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_RepQuality_Poor_Null;
CREATE TABLE DQC_RepQuality_Poor_Null
 (
	WMID			Int,
	ReportedQuality			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_Calcs;
CREATE TABLE DQC_Calcs
 (
	WMID			Int,
	DQC_ReportedQuality			Int,
	DQC_In_Out			Int,
	DQC_HYear			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_Combined_High;
CREATE TABLE DQC_Combined_High
 (
	WMID			Int,
	QualityCalc			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS NC_Hurricanes_Intersect;
CREATE TABLE NC_Hurricanes_Intersect
 (
	Serial_Num			Varchar (510),
	ID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS IBTrACS_Lat_Long;
CREATE TABLE IBTrACS_Lat_Long
 (
	Name			Varchar (510),
	Season			Double,
	Latitude			Double,
	Longitude			Double,
	Serial_Num			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS NC_Hurricanes_TrackPoints;
CREATE TABLE NC_Hurricanes_TrackPoints
 (
	Name			Varchar (510),
	Season			Double,
	Latitude			Double,
	Longitude			Double,
	Serial_Num			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkpVDatum;
CREATE TABLE lkpVDatum
 (
	VDatumID			Int,
	VDatum			Varchar (510),
	Description			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TMPCLP48762;
CREATE TABLE TMPCLP48762
 (
	WMID			Int,
	HYear			Varchar (510),
	In_Out			Varchar (510),
	ReportedQuality			Varchar (510),
	VDatumID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TMPCLP48763;
CREATE TABLE TMPCLP48763
 (
	WMID			Int,
	HYear			Varchar (510),
	ReportedQuality			Varchar (510),
	VDatumID			Int,
	In_Out			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TMPCLP48761;
CREATE TABLE TMPCLP48761
 (
	WMID			Int,
	VDatumID			Int,
	ReportedQuality			Varchar (510),
	In_Out			Varchar (510),
	HYear			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS lkp_DQC_Year_since_1990;
CREATE TABLE lkp_DQC_Year_since_1990
 (
	WMID			Int,
	HYear			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_Combined_Medium;
CREATE TABLE DQC_Combined_Medium
 (
	WMID			Int,
	QualityCalc			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS DQC_Combined_Low;
CREATE TABLE DQC_Combined_Low
 (
	WMID			Int,
	QualityCalc			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TimeofLFFlag;
CREATE TABLE TimeofLFFlag
 (
	HID			Int,
	ISO_time			Date ,
	LSFlag			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Rad_Landfall;
CREATE TABLE Rad_Landfall
 (
	HID			Int,
	atcf_rmw			Double,
	Diam_LF			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Convert_Wind_1min;
CREATE TABLE Convert_Wind_1min
 (
	HID			Int,
	LFWind			Int,
	1_min_Winds			Int,
	LFCat			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TimeofLFFlag_LF2;
CREATE TABLE TimeofLFFlag_LF2
 (
	HID			Int,
	ISO_time			Date ,
	LSFlag_LF2			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Location_LFFlag_LF2;
CREATE TABLE Location_LFFlag_LF2
 (
	HID			Int,
	Latitude			Double,
	Longitude			Double,
	LSFlag_LF2			Varchar (510)
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS TMPCLP491781;
CREATE TABLE TMPCLP491781
 (
	LSFlag_LF2			Varchar (510),
	HID			Int,
	atcf_rmw			Double,
	Wind_WMO			Double,
	Pres_WMO			Double,
	HLFID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Max_WindPress_LF2;
CREATE TABLE Max_WindPress_LF2
 (
	HID			Int,
	MaxOfWind_WMO			Double,
	MaxOfPres_WMO			Double,
	LSFlag_LF2			Varchar (510),
	HLFID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS MinPres_LF2;
CREATE TABLE MinPres_LF2
 (
	HID			Int,
	MinOfPres_WMO			Double,
	LSFlag_LF2			Varchar (510),
	HLFID			Int
);
-- CREATE ANY INDEXES ...

DROP TABLE IF EXISTS Radius_Landfall_LF2;
CREATE TABLE Radius_Landfall_LF2
 (
	HID			Int,
	ISO_time			Date ,
	atcf_rmw			Double,
	LSFlag_LF2			Varchar (510),
	DiameterLF			Int
);
-- CREATE ANY INDEXES ...



-- CREATE ANY Relationships ...

-- relationships are not supported for access
