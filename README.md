# bash-json

This util maps json data to a directory structure in your file system for further usage in shell.

## Mappingrules:

   - objects / arrays are mapped to directorys
   - primitive properties are mapped to files containing the value

## Dependencies

   - PHP

## Usage

        data=<json-data>
        directory="/path/to";
        root_node="usage";

        php bashjson.php --root="${root_node}" --directory="${directory}" "${foo}"

        # evaluate $?

        # cleanup $directory. bashjson will never remove anything!

    There is an example script in example.sh.

## Return values

    1, if argv is not available
    2. if json is not correct json
    3. if a directory could not created
    4. if a file could not created

## Params

  - --directory=/path/to/ json data will be created under ths directory in the root node.
  - --root=<name> creates a root node <name> in which the json data will be created
  - last argument is the json data as string

## License

The Software shall be used for Good, not Evil.
