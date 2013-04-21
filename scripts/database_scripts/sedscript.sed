#!/bin/sed -f
# We need to format the Access database types to MySQL types:
s/\<Long Integer\>/Int/
s/\<Text\>/Varchar/
s/\<Memo\/Hyperlink\>/Text/
s/\<Single\>/Float/
s/\<DateTime\>/Date/
s/(Short)//

# Some of the tablesd have tildas (~) in them, so let's get rid of those:
s/~//

# Some of the columns are named things that are reserved words in MySQL, so
# let's take care of that as well:
s/\<InOut\>/In_Out/

# Some of the Longitude and Latitude value fields are named different things,
# let's keep it consistent:
s/\<LFLong\>/Longitude/g
s/\<LFLat\>/Latitude/g

# For some reason, mdbtools doesn't like to add this little caviat of the table
# needing to exist to be able to delete it. Let's fix that:
s/\<DROP TABLE\>/DROP TABLE IF EXISTS/

# mdbtools also likes to use dashes as seperators, however, if you don't put
# a space after the first two, MySQL tries to interpret it, so let's fix that:
s/---/-- /

# Let's remove trailing tabs (because I don't like them):
s/[ \t]*$//

# For some reason, they accidentally added a space into this Hurricane name,
# so MySQL can't find it if you search for 'Hazel', we should fix that, too:
s/"Hazel "/"Hazel"/

# This section formats the DateTime fields of the HurricaneTrackPoints. It
# prepends a 0 to any month or dat that only has one digit because MySQL
# needs 2, and then it changes the format from MM/DD/YYYY to YYYY/MM/DD:
# Credits: Dr. Andy Dalton
s|"\([0-9]\)/|"0\1/|
s|/\([0-9]\)/|/0\1/|
s|\([0-9]*\)/\([0-9]*\)/\([0-9]*\)|\3/\1/\2|

# This section fixes the time section of the DateTime fields to be in military
# time because that is what MySQL needs:
s/12:00:00 AM/00:00:00/
s/6:00:00 AM/06:00:00/
s/12:00:00 PM/12:00:00/
s/6:00:00 PM/18:00:00/
