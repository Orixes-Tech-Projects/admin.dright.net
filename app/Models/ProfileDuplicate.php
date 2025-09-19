<?php

namespace App\Models;

use App\Models\Crud;
use CodeIgniter\Model;

class ProfileDuplicate extends Model
{
    protected $crud;

    public function __construct()
    {
        parent::__construct();
        $this->crud = new Crud();
    }

    /**
     * Generic method to copy data from one table to another for ProfileUID-based records.
     */
    private function copyTableData($table, $CopyProfileUID, $ProfileUID, $extraFields = [], $additionalWhere = '', $deepCopyCallback = null)
    {
        $whereClause = '"ProfileUID" = \'' . $CopyProfileUID . '\' ' . $additionalWhere;
        $sql = "SELECT * FROM $table WHERE $whereClause";

        $records = $this->crud->ExecutePgSQL($sql);

        if (!empty($records)) {
            $this->crud->DeleteRecordPG($table, ["ProfileUID" => $ProfileUID]);

            foreach ($records as $row) {
                $CopyUID = $row['UID'] ?? null;
                unset($row['UID']);

                $row['ProfileUID'] = $ProfileUID;
                if ($table != '"public"."options"') {
                    $row['SystemDate'] = date("Y-m-d H:i:s");
                }

                $row = array_merge($row, $extraFields);

                $newID = $this->crud->AddRecordPG($table, $row);

                // Callback for deep copy if applicable
                if (is_callable($deepCopyCallback) && $newID && $CopyUID) {
                    $deepCopyCallback($CopyUID, $newID);
                }
            }
        }
    }

    /**
     * Copy related images for a gallery
     */
    private function copyGalleryImages($OldGalleryUID, $NewGalleryUID)
    {
        $this->copyChildTable(
            '"public"."gallery_images"',
            'GalleryUID',
            $OldGalleryUID,
            $NewGalleryUID
        );
    }

    /**
     * Copy hospital clinic timings
     */
    private function copyHospitalClinicTimings($OldClinicUID, $NewClinicUID)
    {
        $this->copyChildTable(
            '"public"."hospitals_clinics_timings"',
            'HospitalClinicsUID',
            $OldClinicUID,
            $NewClinicUID
        );
    }

    /**
     * Generic method to copy child table data
     */
    private function copyChildTable($table, $foreignKey, $oldID, $newID)
    {
        $sql = "SELECT * FROM $table WHERE \"$foreignKey\" = '$oldID'";
        $records = $this->crud->ExecutePgSQL($sql);

        if (!empty($records)) {
            $this->crud->DeleteRecordPG($table, [$foreignKey => $newID]);

            foreach ($records as $row) {
                unset($row['UID']);
                $row[$foreignKey] = $newID;
                $row['SystemDate'] = date("Y-m-d H:i:s");

                $this->crud->AddRecordPG($table, $row);
            }
        }
    }

    // Reusable wrapper methods

    public function GetProfileMetaRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."profile_metas"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileOptionsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."options"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileBannersRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."banner"', $CopyProfileUID, $ProfileUID, [], 'AND "Status" = 1');
    }

    public function GetProfileSpecialtiesRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."services"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileFacilitiesRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."facilities"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileAuthorsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."authors"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileBlogsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."blogs"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileNewsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."news"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileReviewsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."patients"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileGalleryRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData(
            '"public"."gallery"',
            $CopyProfileUID,
            $ProfileUID,
            [],
            '',
            function ($oldID, $newID) {
                $this->copyGalleryImages($oldID, $newID);
            }
        );
    }

    public function GetDoctorProfileHospitalClinicsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData(
            '"public"."hospitals_clinics"',
            $CopyProfileUID,
            $ProfileUID,
            [],
            '',
            function ($oldID, $newID) {
                $this->copyHospitalClinicTimings($oldID, $newID);
            }
        );
    }

    public function GetProfileAwardsAndMemberShipRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."awards_membership"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileGraduationRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."graduation"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfilePostGraduationRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."post_graduation"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileExperienceRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $this->copyTableData('"public"."experience"', $CopyProfileUID, $ProfileUID);
    }

    public function GetProfileDoctorsRecordAndInsert($CopyProfileUID, $ProfileUID)
    {
        $Crud = new Crud();

        $CopyProfileUID = (int)$CopyProfileUID;
        $ProfileUID = (int)$ProfileUID;

        $SQL = 'SELECT * FROM "public"."profile_metas" 
            WHERE "Option" = \'parent_id\' 
            AND CAST("Value" AS INTEGER) = ' . $CopyProfileUID;

        $ProfileDoctorsRecord = $Crud->ExecutePgSQL($SQL);

        if (count($ProfileDoctorsRecord) > 0) {

            $Crud->DeleteRecordPG(
                '"public"."profile_metas"',
                array(
                    "Option" => 'parent_id',
                    "Value" => (string)$ProfileUID
                )
            );

            foreach ($ProfileDoctorsRecord as $row) {

                unset($row['UID']);
                $row['Value'] = (string)$ProfileUID;
                $row['SystemDate'] = date("Y-m-d H:i:s");

                $Crud->AddRecordPG('"public"."profile_metas"', $row);
            }
        }
    }

}
